<?php


namespace App\Enums;


class ServerStatusEnum extends Enum
{
    const CREATED = "created";
    const STARTED = "started";
    const RESTARTED = "restarted";
    const PLAY = "play";
    const FINISHED = "finished";
    const DELETED = "deleted";

    protected static $map = [
        "CREATED" => self::CREATED,
        "STARTED" => self::STARTED,
        "RESTARTED" => self::RESTARTED,
        "PLAY" => self::PLAY,
        "FINISHED" => self::FINISHED,
        "DELETED" => self::DELETED,
    ];
}
