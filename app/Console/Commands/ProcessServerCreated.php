<?php

namespace App\Console\Commands;

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
    protected $description = 'Command description';


    /** @var ServerService */
    protected $serverService;


    public function __construct(ServerService $serverService)
    {
        parent::__construct();

        $this->serverService = $serverService;
    }


    public function handle()
    {
        $this->serverService->startServer(1);
        return true;
    }
}
