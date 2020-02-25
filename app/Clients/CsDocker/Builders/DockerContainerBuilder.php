<?php


namespace App\Clients\CsDocker\Builders;


class DockerContainerBuilder
{
    /** @var array */
    private $environments = [];

    /** @var array */
    private $portMappings = [];

    /** @var string */
    private $name = 'example';

    /** @var string */
    private $imageName = 'hello-world';

    public function addEnv(string $name, string $value): self
    {
        $this->environments[$name] = $value;
        return $this;
    }

    public function addPortMapping(int $hostPort, int $containerPort): self
    {
        $this->portMappings[$hostPort] = $containerPort;
        return $this;
    }

    public function setContainerName($name): self
    {
        $this->name = $name;
        return $this;
    }

    public function setImage($image): self
    {
        $this->imageName = $image;
        return $this;
    }

    public function getCommand(): string
    {
        $command = 'docker run -d';

        ksort($this->portMappings);
        foreach ($this->portMappings as $hostPort => $containerPort) {
            $command .= ' -p ' . $hostPort . ':' . $containerPort . '/tcp';
            $command .= ' -p ' . $hostPort . ':' . $containerPort . '/udp';
        }

        ksort($this->environments);
        foreach ($this->environments as $name => $value){
            $command .= ' -e ' . $name . '="'.$this->escapeShellString($value).'"';
        }

        $command .= ' --name ' . $this->name;
        $command .= ' ' . $this->imageName;

        return $command;
    }

    private function escapeShellString($str)
    {
        return str_replace('"', '\"', $str);
    }
}
