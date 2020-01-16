<?php

namespace App\Console\Commands;

use App\Clients\CsDocker\CsDockerClient;
use App\Enums\ServerStatusEnum;
use App\Repositories\ServerRepository;
use App\Services\Log\Log;
use Illuminate\Console\Command;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'test';

    /** @var Log */
    protected $log;


    public function __construct(Log $log)
    {
        parent::__construct();

        $this->log = $log->setComponent('command.test')->console();
    }


    public function handle()
    {
        try {
            $this->log->debug(env("APP_DEBUG"));
        } catch (\Exception $e) {
            $this->log->exception($e);
        }

        return true;
    }
}
