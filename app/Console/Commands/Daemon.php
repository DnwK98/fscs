<?php


namespace App\Console\Commands;


use App\Clients\CsDocker\CsDockerClient;
use App\Enums\ServerStatusEnum;
use App\Repositories\ServerRepository;
use App\Services\Log\Log;
use DB;
use Illuminate\Console\Command;

class Daemon extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:daemon';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Background process running for application background work';

    /** @var Log */
    protected $log;

    public function __construct(Log $log)
    {
        parent::__construct();

        $this->log = $log->setComponent('daemon')->console();
    }


    public function handle()
    {
        $this->log->info("Initializing application...");
        $this->initializeApplication();

        $this->log->info("Daemon started");
        while (true){
            $this->mainLoop();
            sleep(10);
        }
    }

    private function mainLoop()
    {
        //
    }

    private function initializeApplication()
    {
        $this->waitForDbOrExitWithError();

        $this->log->info("Migrating...");
        $this->call('migrate', [
            '--no-interaction' => true,
            '--force' => true
        ]);
    }

    private function waitForDbOrExitWithError()
    {
        $retires = 5;
        $sleepTime = 5;
        while (true) {
            try {
                $this->log->info("Waiting for database...");
                $pdoConnection = DB::connection()->getPdo();
                break;
            } catch (\Exception $exception) {
                if (--$retires <= 0) {
                    $this->log->exception($exception);
                    exit(1);
                }
                sleep($sleepTime);
            }
        }
    }
}
