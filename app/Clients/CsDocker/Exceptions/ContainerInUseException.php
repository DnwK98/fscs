<?php


namespace App\Clients\CsDocker\Exceptions;


use Throwable;

class ContainerInUseException extends CsDockerClientException
{
    private $output;

    public function __construct($output = "No console output", $message = "Port is busy", $code = 0, Throwable $previous = null)
    {
        $this->output = $output;

        parent::__construct($message, $code, $previous);
    }

    public function getOutput()
    {
        return $this->output;
    }
}
