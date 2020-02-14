<?php

namespace App\Services\Log;

use Log as LaravelLog;

class Log
{
    /** @var string */
    protected $component = null;

    protected $debug = false;

    protected $console = false;

    public function __construct()
    {
        $this->debug = env("APP_DEBUG") === "true";
    }

    public function console(bool $console = true)
    {
        $this->console = $console;
        return $this;
    }

    public function setComponent(string $component)
    {
        $this->component = $component;
        return $this;
    }

    public function info($message)
    {
        $this->write($message, "info");
    }

    public function notice($message)
    {
        $this->write($message, "notice");
    }

    public function error($message)
    {
        $this->write($message, "error");
    }

    public function exception(\Exception $exception)
    {
        $message = $exception->__toString();
        $this->write($message, "error");
    }

    public function debug($message)
    {
        $this->write($message, "debug");
    }

    private function write($message, $level = "info")
    {
        // Allow to log whole objects if required
        $message = print_r($message, true);

        // Append component string if set
        if(null !== $this->component){
            $message = "[{$this->component}] $message";
        }

        // Add stack trace to easier debug
        if(
            $this->debug &&
            in_array($level, ['debug', 'error'])
        ) {
            $stackTrace = (new \Exception())->getTraceAsString();
            $message .= " | Trace: " . $stackTrace;
        }

        // Replace new lines with two spaces to easier grep logs
        $message = preg_replace('~[\r\n]+~', '  ', $message);

        // Log using internal laravel logger
        LaravelLog::{$level}($message);

        // Print log in console if is set up
        if($this->console) {
            echo sprintf("[%s][%s]%s\n",
                date('Y-m-d H:i:s'),
                $level,
                $message
            );
        }
    }
}
