<?php


namespace App\Services\Server;


use App\Clients\CsDocker\CsDockerClient;
use App\Clients\CsDocker\Exceptions\ContainerInUseException;
use App\Enums\MapEnum;
use App\Enums\ServerStatusEnum;
use App\Events\MatchFinishedEvent;
use App\Events\MatchStartedEvent;
use App\Events\ServerRestartedEvent;
use App\Events\ServerStartedEvent;
use App\Get5StatsMap;
use App\Repositories\Get5StatsRepository;
use App\Repositories\ServerRepository;
use App\Server;
use App\ServerTeam;
use App\Services\Event\EventPropagationService;
use App\Services\Log\Log;
use App\Services\Server\Configuration\MatchConfiguration;
use App\Services\Server\Configuration\TeamConfiguration;
use App\Services\Server\Dto\ServerDto;
use App\TeamPlayer;
use Illuminate\Validation\ValidationException;
use Symfony\Component\VarDumper\VarDumper;

class ServerService
{
    /** @var CsDockerClient */
    private $docker;

    /** @var ServerMappingService */
    private $mappingService;

    /** @var EventPropagationService */
    private $eventPropagator;

    /** @var ServerRepository */
    private $repository;

    /** @var Get5StatsRepository */
    private $get5Repository;

    /** @var Log */
    private $log;

    public function __construct(
        CsDockerClient $docker,
        ServerMappingService $mappingService,
        EventPropagationService $eventPropagator,
        ServerRepository $repository,
        Get5StatsRepository $get5Repository,
        Log $log
    )
    {
        $this->docker = $docker;
        $this->mappingService = $mappingService;
        $this->eventPropagator = $eventPropagator;
        $this->repository = $repository;
        $this->get5Repository = $get5Repository;
        $this->log = $log->setComponent("app.service.server");
    }

    public function processServerCreated(Server $server)
    {
        $this->log->info("Starting server {$server->id}");
        if ($this->startServer($server)) {
            $server->status = ServerStatusEnum::STARTED;
            $server->save();
            $this->log->info("Server {$server->id} started successfully");

            $this->eventPropagator->propagate(
                new ServerStartedEvent($server->id)
            );
        } else {
            $this->log->error("Server {$server->id} could not start");
        }
    }

    public function processServerStarted(Server $server)
    {
        $map = $this->get5Repository->getMapByMatchId($server->match_id);
        if ($map instanceof Get5StatsMap) {
            $this->log->info("Match on server");
            if ($map->hasScore()) {
                $server->status = ServerStatusEnum::PLAY;
                $server->save();
                $this->log->info("Match on server {$server->id} has started");
                $this->eventPropagator->propagate(
                    new MatchStartedEvent($server->id)
                );
            }
        }
    }

    public function processServerRestarted(Server $server)
    {
        $this->log->info("Restarting server {$server->id} on port {$server->port}");
        if($this->restartServer($server)){
            $server->status = ServerStatusEnum::STARTED;
            $server->save();
            $this->log->info("Server {$server->id} restarted successfully");
        } else {
            $this->log->error("Server {$server->id} could not restart");
        }
    }

    public function processServerPlay(Server $server)
    {
        $map = $this->get5Repository->getMapByMatchId($server->match_id);
        if ($map instanceof Get5StatsMap) {
            if ($map->isFinished()) {
                $this->log->info("Match on server {$server->id} has finished, removing server");
                $this->killServer($server);
                $server->status = ServerStatusEnum::FINISHED;
                $server->save();
                $this->eventPropagator->propagate(
                    new MatchFinishedEvent($server->id)
                );
            }
        }
    }

    /**
     * @param int $id
     * @return ServerDto|null
     */
    public function getServerById(int $id)
    {
        $server = $this->repository->getById($id);
        if (!$server instanceof Server) {
            return null;
        }
        $get5Map = $this->get5Repository->getMapByMatchId($server->match_id);
        $get5Players = $this->get5Repository->getPlayersByMatchId($server->match_id);

        return $this->mappingService->mapServerToDtoWithStatistics($server, $get5Map, $get5Players);
    }

    /**
     * @param array $jsonServer
     * @return Server
     */
    public function createServer(array $jsonServer): Server
    {
        $server = new Server();
        $server->status = ServerStatusEnum::CREATED;
        if(isset($jsonServer['id'])){
            $server->id = $jsonServer['id'];
        }

        $server->map = $jsonServer['map'];
        $server->port = $jsonServer['port'];

        $teamNumber = 1;
        foreach ($jsonServer['teams'] as $jsonTeam){
            $team = new ServerTeam();
            $team->name = $jsonTeam['name'];
            $team->team_number = $teamNumber ++;
            $team->tag = $jsonTeam['tag'];
            foreach ($jsonTeam['players'] as $jsonPlayer){
                $player = new TeamPlayer();
                $player->steam_id_64 = $jsonPlayer['steamId64'];
                $player->name = isset($jsonPlayer['name']) ? $jsonPlayer['name'] : null;
                $team->players[] = $player;
            }
            $server->teams[] = $team;
        }

        $this->repository->save($server);
        return $server;
    }

    public function startServer(Server $server): bool
    {
        $configuration = new MatchConfiguration();

        $configuration->setMatchId($server->match_id);
        $configuration->setHostName("Test server");
        $configuration->setMap($server->map);

        foreach ($server->teams as $team) {
            $teamConfig = new TeamConfiguration();
            $teamConfig->setName($team->name);
            $teamConfig->setTag($team->tag);
            foreach ($team->players as $player) {
                $teamConfig->addPlayer($player->steam_id_64, $player->name);
            }
            $configuration->addTeam($teamConfig);
        }

        $port = $server->port;

        return $this->tryRunServer($port, $configuration);
    }

    public function restartServer(Server $server)
    {
        $this->killServer($server);
        if ($this->startServer($server)) {
            $this->eventPropagator->propagate(
                new ServerRestartedEvent($server->id)
            );

            return true;
        }
        return false;
    }

    public function killServer(Server $server)
    {
        return $this->docker->rm($server->port);
    }

    private function containerRunningOnPort($port)
    {
        $containers = $this->docker->ps();
        return in_array('fscs_server_' . $port, $containers);
    }

    /**
     * @param int $port
     * @param MatchConfiguration $configuration
     * @return bool
     */
    public function tryRunServer(int $port, MatchConfiguration $configuration): bool
    {
        try {
            $this->docker->run($port, $configuration);
        } catch (ContainerInUseException $e) {
            $this->log->info("Container in use $port");
            if ($this->containerRunningOnPort($port)) {
                $this->log->error("There is other container $port");
                return false;
            } else {
                $this->log->info("Removing container $port");
                $this->docker->rm($port);
                $this->docker->run($port, $configuration);
            }
        }

        if (!$this->containerRunningOnPort($port)) {
            $this->log->error("After creating server $port it's not visible in docker ps");
            $this->log->error($this->docker->ps());
            return false;
        }

        return true;
    }
}
