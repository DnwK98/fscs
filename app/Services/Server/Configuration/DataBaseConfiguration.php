<?php


namespace App\Services\Server\Configuration;


class DataBaseConfiguration
{
    private $dbHost;
    private $dbName;
    private $dbUser;
    private $dbPassword;

    public function __construct()
    {
        $this->dbHost = "host.docker.internal";
        $this->dbName = env("DB_DATABASE");
        $this->dbUser = env("DB_USERNAME");
        $this->dbPassword = env("DB_PASSWORD");
    }


    public function getString()
    {
        return <<<EOF
"Databases"
{
	"driver_default"		"mysql"

	"default"
	{
		"driver"			"default"
		"host"				"localhost"
		"database"			"sourcemod"
		"user"				"root"
		"pass"				""
		//"timeout"			"0"
		//"port"			"0"
	}

	"get5"
	{
		"driver"			"default"
		"host"				"{$this->dbHost}"
		"database"			"{$this->dbName}"
		"user"				"{$this->dbUser}"
		"pass"				"{$this->dbPassword}"
		//"timeout"			"0"
		//"port"			"0"
	}

	"storage-local"
	{
		"driver"			"sqlite"
		"database"			"sourcemod-local"
	}

	"clientprefs"
	{
		"driver"			"sqlite"
		"host"				"localhost"
		"database"			"clientprefs-sqlite"
		"user"				"root"
		"pass"				""
		//"timeout"			"0"
		//"port"			"0"
	}
}
EOF;

    }
}
