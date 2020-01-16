<?php


namespace App\Services\Server\Configuration;


class DataBaseConfiguration
{
    public $dbPort;
    public $dbName;
    public $dbUser;
    public $dbPassword;

    public function __construct()
    {
        $this->dbPort = env("DB_PORT");
        $this->dbName = env("DB_DATABASE");
        $this->dbUser = env("DB_USERNAME");
        $this->dbPassword = env("DB_PASSWORD");
    }
}
