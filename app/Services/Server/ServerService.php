<?php


namespace App\Services\Server;


use App\Clients\CsDocker\CsDockerClient;
use App\Repositories\ServerRepository;
use App\Services\Server\Configuration\MatchConfiguration;
use App\Services\Server\Configuration\TeamConfiguration;

class ServerService
{
    /** @var CsDockerClient */
    private $docker;

    /** @var ServerRepository */
    private $repository;

    public function __construct(CsDockerClient $docker, ServerRepository $repository)
    {
        $this->docker = $docker;
        $this->repository = $repository;
    }

    public function createServer()
    {

    }

    public function startServer($id): bool
    {
        $this->docker->run(27015, new MatchConfiguration());

        //sleep(5);

//        $this->docker->rm(27015);

        return true;
        $server = $this->repository->getById($id);
        if(!$server){
            return false;
        }

        $configuration = new MatchConfiguration();

        foreach ([1,2] as $teamNumber){
            $team = new TeamConfiguration();

            foreach ($server->players($teamNumber) as $player){
                $team->addPlayer($player->steamId64, $player->name);
            }

            $configuration->addTeam($team);
        }

        $this->docker->run($server->port, $configuration);
    }

    public function killServer($id)
    {

    }

    public function restartServer($id)
    {

    }
}
