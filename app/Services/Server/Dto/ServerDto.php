<?php


namespace App\Services\Server\Dto;


class ServerDto
{
    /** @var int */
    public $id;

    /** @var int */
    public $port;

    /** @var string */
    public $status;

    /** @var string */
    public $map;

    /** @var TeamDto[] */
    public $teams = [];
}
