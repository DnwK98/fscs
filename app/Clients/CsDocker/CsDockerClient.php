<?php


namespace App\Clients\CsDocker;


use App\Clients\CsDocker\Builders\DockerContainerBuilder;
use App\Clients\CsDocker\Exceptions\ContainerInUseException;
use App\Clients\CsDocker\Exceptions\ServerImageNotFoundException;
use App\Services\Server\Configuration\DataBaseConfiguration;
use App\Services\Server\Configuration\MatchConfiguration;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class CsDockerClient
{
    /** @var DataBaseConfiguration */
    private $dataBaseConfiguration;

    /** @var string */
    private $imageName = "fscs_server_image";

    /** @var string */
    private $containerPrefix = "fscs_server_";

    public function __construct(DataBaseConfiguration $dataBaseConfiguration)
    {
        $this->dataBaseConfiguration = $dataBaseConfiguration;
    }

    public function run(int $port, MatchConfiguration $matchConfig)
    {
        $jsonMatchConfig = $matchConfig->generateJson();
        $db = $this->dataBaseConfiguration;

        $containerBuilder = new DockerContainerBuilder();
        $containerBuilder
            ->addPortMapping($port, $port)
            ->addEnv('SERVER_HOSTNAME', 'fscs_dev_server')
            ->addEnv('RCON_PASSWORD', 'SN94NF7DK3')
            ->addEnv('STEAM_ACCOUNT', '8C5C4A8275069E74A8C6AA871C96D741')
            ->addEnv('MAXPLAYERS', '12')
            ->addEnv('MAP', $matchConfig->getMap())
            ->addEnv('JSON_MATCH_CONFIGURATION', $jsonMatchConfig)
            ->addEnv('DB_USER', $db->dbUser)
            ->addEnv('DB_PASSWORD', $db->dbPassword)
            ->addEnv('DB_NAME', $db->dbName)
            ->addEnv('DB_PORT', $db->dbPort)
            ->addEnv('MATCH_ID', $matchConfig->getMatchId())
            ->addEnv('SERVER_PORT', $port)
            ->setContainerName($this->containerPrefix . $port)
            ->setImage($this->imageName);

        $command = $containerBuilder->getCommand();

        try {
            return $this->exec($command);
        } catch (ProcessFailedException $e){
            $process = $e->getProcess();
            $output = $process->getOutput() . "||" . $process->getErrorOutput();

            if(strpos($output, "is already in use by container") !== false) {
                throw new ContainerInUseException($output);
            } elseif(strpos($output, "Unable to find image") !== false){
                throw new ServerImageNotFoundException($output);
            }

            // Rethrow if it is not common error
            throw $e;
        }
    }

    public function ps(): array
    {
        $output = $this->exec("docker ps --format {{.Names}}");
        $output = trim($output);

        return explode("\n", $output);
    }

    public function images():array
    {
        $output = $this->exec("docker images --format {{.Repository}}");
        $output = trim($output);

        return explode("\n", $output);
    }

    public function serverImageExists()
    {
        return in_array($this->imageName, $this->images());
    }

    public function rm($port)
    {
        try {
            $this->exec("docker rm -f {$this->containerPrefix}{$port}");
            return true;
        } catch (ProcessFailedException $e) {
            $process = $e->getProcess();
            $output = $process->getOutput() . "||" . $process->getErrorOutput();

            if(strpos($output, "No such container") !== false){
                return false;
            }

            // Rethrow if it is not common error
            throw $e;
        }
    }

    public function forceRmAll()
    {
        foreach ($this->ps() as $name) {
            if(strpos($name, $this->containerPrefix) !== false){
                $this->rm(str_replace($this->containerPrefix, "", $name));
            }
        }
    }

    public function build()
    {
        $dir = __DIR__;
        $timeout2Hours = 2 * 60 * 60;
        return $this->exec(
            "cd $dir/DockerFiles &&  docker build -f Create/Dockerfile --no-cache -t {$this->imageName} .",
            $timeout2Hours
        );
    }

    public function buildUpdate()
    {
        $dir = __DIR__;
        $timeout30Minutes = 60 * 30;
        return $this->exec(
            "cd $dir/DockerFiles &&  docker build -f Update/Dockerfile --no-cache -t {$this->imageName} .",
            $timeout30Minutes
        );
    }

    /**
     * @param $command
     * @param int $timeout
     * @return string
     */
    private function exec($command, $timeout = 300)
    {
        // CRLF creates issues with multi line command so replace it
        // for possibility of editing in Windows environment
        $command = str_replace("\r\n", "\n", $command);

        $process = Process::fromShellCommandline($command);
        $process->setTimeout($timeout);
        $process->mustRun();

        return $process->getOutput();
    }
}
