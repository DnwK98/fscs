<?php


namespace App\Services\Server\Dto;


class PlayerDto
{
    /** @var string */
    public $steamId64;

    /** @var string */
    public $name;

    /** @var int */
    public $kills;

    /** @var int */
    public $deaths;

    /** @var int */
    public $assists;

    /** @var int */
    public $headShots;

    /** @var int */
    public $damageDealt;
}
