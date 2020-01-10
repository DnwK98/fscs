<?php


namespace App\Clients\CsDocker;


use App\Services\Server\Configuration\DataBaseConfiguration;
use App\Services\Server\Configuration\MatchConfiguration;
use Symfony\Component\Process\Process;

class CsDockerClient
{
    /** @var DataBaseConfiguration */
    private $dataBaseConfiguration;

    public function __construct(DataBaseConfiguration $dataBaseConfiguration)
    {
        $this->dataBaseConfiguration = $dataBaseConfiguration;
    }

    public function run(int $port, MatchConfiguration $matchConfig)
    {
        $jsonMatchConfig = $this->escapeShellString($matchConfig->generateJson());
        $dataBaseConfig = $this->escapeShellString($this->dataBaseConfiguration->getString());

        $command = '
            docker run \
                -d \
                -p '.$port.':27015/tcp \
                -p '.$port.':27015/udp \
                -e SERVER_HOSTNAME="fscs_dev_server" \
                -e RCON_PASSWORD="SN94NF7DK3" \
                -e MAXPLAYERS="12" \
                -e STEAM_ACCOUNT="8C5C4A8275069E74A8C6AA871C96D741" \
                -e JSON_MATCH_CONFIGURATION="'.$jsonMatchConfig.'" \
                -e DATA_BASE_CONFIGURATION="'.$dataBaseConfig.'" \
                -e MATCH_ID="10003" \
                --name fscs_server_'.$port.' \
                fscs_server
        ';

        print_r($command);

        echo $this->exec($command);
    }

    public function rm($port)
    {
        echo $this->exec("docker rm -f fscs_server_$port");
    }

    private function exec($command)
    {
        $process = Process::fromShellCommandline($command);
        $process->mustRun();

        return $process->getOutput();
    }

    private function escapeShellString($str)
    {
        return str_replace('"', '\"', $str);
    }
}
