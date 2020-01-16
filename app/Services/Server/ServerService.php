<?php


namespace App\Services\Server;


use App\Clients\CsDocker\CsDockerClient;
use App\Clients\CsDocker\Exceptions\ContainerInUseException;
use App\Enums\ServerStatusEnum;
use App\Get5StatsMap;
use App\Repositories\Get5StatsRepository;
use App\Repositories\ServerRepository;
use App\Server;
use App\Services\Log\Log;
use App\Services\Server\Configuration\MatchConfiguration;
use App\Services\Server\Configuration\TeamConfiguration;
use Symfony\Component\VarDumper\VarDumper;

class ServerService
{
    /** @var CsDockerClient */
    private $docker;

    /** @var ServerRepository */
    private $repository;

    /** @var Get5StatsRepository */
    private $get5Repository;

    /** @var Log */
    private $log;

    public function __construct(CsDockerClient $docker, ServerRepository $repository, Get5StatsRepository $get5Repository, Log $log)
    {
        $this->docker = $docker;
        $this->repository = $repository;
        $this->get5Repository = $get5Repository;
        $this->log = $log->setComponent("app.service.server")->console();
    }

    public function processServerCreated(Server $server)
    {
        $this->log->info("Starting server {$server->id}");
        if($this->startServer($server)){
            $server->status = ServerStatusEnum::STARTED;
            $server->save();
            $this->log->info("Server {$server->id} started successfully");
            // Send server started event
        } else {
            $this->log->error("Server {$server->id} could not start");
            // Send error event
        }
    }

    public function processServerStarted(Server $server)
    {
        $map = $this->get5Repository->getMapByMatchId($server->match_id);
        if($map instanceof Get5StatsMap){
            $this->log->info("Match on server");
            if($map->hasScore()){
                $server->status = ServerStatusEnum::PLAY;
                $server->save();
                $this->log->info("Match on server {$server->id} has started");
            }
        }
    }

    public function processServerRestarted(Server $server)
    {
        $this->log->info("Restarting server {$server->id} on port {$server->port}");
        $this->restartServer($server);
    }

    public function processServerPlay(Server $server)
    {
        $map = $this->get5Repository->getMapByMatchId($server->match_id);
        if($map instanceof Get5StatsMap){
            if($map->isFinished()){
                $this->log->info("Match on server {$server->id} has finished, removing server");
                $this->killServer($server);
                $server->status = ServerStatusEnum::FINISHED;
                $server->save();

                // Send event match finished with statistics
            }
        }
    }

    public function createServer()
    {

    }

    public function startServer(Server $server): bool
    {
        $configuration = new MatchConfiguration();

        $configuration->setMatchId($server->match_id);
        $configuration->setHostName("Test server");
        $configuration->setMap($server->map);

        foreach ($server->teams as $team){
            $teamConfig = new TeamConfiguration();
            $teamConfig->setName($team->name);
            $teamConfig->setTag($team->tag);
            foreach ($team->players as $player){
                $teamConfig->addPlayer($player->steam_id_64, $player->name);
            }
            $configuration->addTeam($teamConfig);
        }

        $port = $server->port;

        return $this->tryRunServer($port, $configuration);
    }

    public function killServer(Server $server)
    {
        return $this->docker->rm($server->port);
    }

    public function restartServer(Server $server)
    {
        $this->killServer($server);
        return $this->startServer($server);
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

        if(!$this->containerRunningOnPort($port)) {
            $this->log->error("After creating server $port it's not visible in docker ps");
            $this->log->error($this->docker->ps());
            return false;
        }

        return true;
    }
}
