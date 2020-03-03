<?php

namespace App\Console\Commands;

use App\Enums\ServerStatusEnum;
use App\Repositories\ServerRepository;
use App\Services\Log\Log;
use App\Services\Server\DockerServerManagementService;
use App\Services\Server\ServerService;
use Illuminate\Console\Command;

class ProcessServerCreated extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'server:process:created';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process all servers on status created and starts counter-strike server';

    /** @var Log */
    protected $log;

    /** @var DockerServerManagementService */
    protected $dockerServerService;

    /** @var ServerRepository */
    protected $serverRepository;


    public function __construct(Log $log, DockerServerManagementService $dockerServerService, ServerRepository $serverRepository)
    {
        parent::__construct();

        $this->log = $log->setComponent('command.server.process');
        $this->serverRepository = $serverRepository;
        $this->dockerServerService = $dockerServerService;
    }


    public function handle()
    {
        $serversIterator = $this->serverRepository->getAllByStatus(ServerStatusEnum::CREATED);
        foreach ($serversIterator as $server) {
            try {
                $this->dockerServerService->processServerCreated($server);
            } catch (\Exception $e) {
                $this->log->exception($e);
            }
        }

        return true;
    }
}
