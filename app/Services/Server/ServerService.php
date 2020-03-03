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

}
