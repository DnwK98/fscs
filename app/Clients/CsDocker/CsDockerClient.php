<?php


namespace App\Clients\CsDocker;


use App\Clients\CsDocker\Exceptions\ContainerInUseException;
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

        $command = '
            docker run \
                -d \
                -p '.$port.':27015/tcp \
                -p '.$port.':27015/udp \
                -e SERVER_HOSTNAME="fscs_dev_server" \
                -e RCON_PASSWORD="SN94NF7DK3" \
                -e STEAM_ACCOUNT="8C5C4A8275069E74A8C6AA871C96D741" \
                -e MAXPLAYERS="12" \
                -e JSON_MATCH_CONFIGURATION="'.$this->escapeShellString($jsonMatchConfig).'" \
                -e DB_USER="'.$this->escapeShellString($db->dbUser).'" \
                -e DB_PASSWORD="'.$this->escapeShellString($db->dbPassword).'" \
                -e DB_NAME="'.$this->escapeShellString($db->dbName).'" \
                -e DB_PORT="'.$this->escapeShellString($db->dbPort).'" \
                -e MATCH_ID="'.$this->escapeShellString($matchConfig->getMatchId()).'" \
                --name '.$this->containerPrefix.$port.' \
                '.$this->imageName.'
        ';
        try {
            return $this->exec($command);
        } catch (ProcessFailedException $e){
            $process = $e->getProcess();
            $output = $process->getOutput() . "||" . $process->getErrorOutput();

            if(strpos($output, "is already in use by container") !== false){
                throw new ContainerInUseException($output);
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
        $process = Process::fromShellCommandline($command);
        $process->setTimeout($timeout);
        $process->mustRun();

        return $process->getOutput();
    }

    private function escapeShellString($str)
    {
        return str_replace('"', '\"', $str);
    }
}
