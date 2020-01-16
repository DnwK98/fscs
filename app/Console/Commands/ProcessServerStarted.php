<?php

namespace App\Console\Commands;

use App\Enums\ServerStatusEnum;
use App\Repositories\ServerRepository;
use App\Services\Log\Log;
use App\Services\Server\ServerService;
use Illuminate\Console\Command;

class ProcessServerStarted extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'server:process:started';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process all servers on status started';

    /** @var Log */
    protected $log;

    /** @var ServerService */
    protected $serverService;

    /** @var ServerRepository */
    protected $serverRepository;


    public function __construct(Log $log, ServerService $serverService, ServerRepository $serverRepository)
    {
        parent::__construct();

        $this->log = $log->setComponent('command.server.process');
        $this->serverService = $serverService;
        $this->serverRepository = $serverRepository;
    }


    public function handle()
    {
        $this->log->debug("Execute ProcessServerStarted");
        $serversIterator = $this->serverRepository->getAllByStatus(ServerStatusEnum::STARTED);
        foreach ($serversIterator as $server) {
            try {
                $this->serverService->processServerStarted($server);
            } catch (\Exception $e) {
                $this->log->exception($e);
            }
        }

        return true;
    }
}
