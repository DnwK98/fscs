<?php

namespace App\Console\Commands;

use App\Clients\CsDocker\CsDockerClient;
use App\Enums\ServerStatusEnum;
use App\Repositories\ServerRepository;
use App\Services\Log\Log;
use Illuminate\Console\Command;

class BuildServerImage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'image:build';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Builds server image for cs docker';

    /** @var Log */
    protected $log;

    /** @var CsDockerClient */
    protected $docker;

    /** @var ServerRepository */
    protected $serverRepository;


    public function __construct(Log $log, CsDockerClient $docker, ServerRepository $serverRepository)
    {
        parent::__construct();

        $this->log = $log->setComponent('command.image.build');
        $this->docker = $docker;
        $this->serverRepository = $serverRepository;
    }


    public function handle()
    {
        try {
            $omitStatuses = [
                ServerStatusEnum::CREATED,
                ServerStatusEnum::STARTED,
                ServerStatusEnum::PLAY,
                ServerStatusEnum::RESTARTED
            ];

            if(($count = $this->serverRepository->getCountByStatus($omitStatuses)) > 0){
                $this->log->info("Omitting image update due to $count active servers");
                return true;
            }

            $timeStart = microtime(true);

            if($this->docker->serverImageExists()){
                $this->log->info("Started docker image update");
                $this->docker->buildUpdate();
            } else {
                $this->log->info("Started docker image build");
                $this->docker->build();
            }

            $executionTime = floor(microtime(true) - $timeStart);
            $this->log->info("Docker image built in $executionTime s");
        } catch (\Exception $e) {
            $this->log->exception($e);
        }

        return true;
    }
}
