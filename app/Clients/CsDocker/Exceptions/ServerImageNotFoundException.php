<?php


namespace App\Clients\CsDocker\Exceptions;


use App\Clients\CsDocker\CsDockerClient;
use Throwable;

class ServerImageNotFoundException extends CsDockerClientException
{
    private $output;

    public function __construct($output = "No console output", $message = "Server image not found", $code = 0, Throwable $previous = null)
    {
        $this->output = $output;

        parent::__construct($message, $code, $previous);
    }

    public function getOutput()
    {
        return $this->output;
    }
}
