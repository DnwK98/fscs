<?php

namespace App\Console\Commands;

use App\Enums\ServerStatusEnum;
use App\Repositories\ServerRepository;
use App\Services\Log\Log;
use App\Services\Server\DockerServerManagementService;
use App\Services\Server\ServerService;
use Illuminate\Console\Command;

class ProcessServerPlay extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'server:process:play';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process all servers on status play, if match finished kill cs server and send event';

    /** @var Log */
    protected $log;

    /** @var DockerServerManagementService */
    protected $serverService;

    /** @var ServerRepository */
    protected $serverRepository;


    public function __construct(Log $log, DockerServerManagementService $serverService, ServerRepository $serverRepository)
    {
        parent::__construct();

        $this->log = $log->setComponent('command.server.process');
        $this->serverService = $serverService;
        $this->serverRepository = $serverRepository;
    }


    public function handle()
    {
        $this->log->debug("Execute ProcessServerPlay");
        $serversIterator = $this->serverRepository->getAllByStatus(ServerStatusEnum::PLAY);
        foreach ($serversIterator as $server) {
            try {
                $this->serverService->processServerPlay($server);
            } catch (\Exception $e) {
                $this->log->exception($e);
            }
        }

        return true;
    }
}
