<?php


namespace App\Enums;


class MapEnum extends Enum
{
    const DE_DUST2 = "de_dust2";
    const DE_MIRAGE = "de_mirage";

    protected static $map = [
        "DE_DUST2" => self::DE_DUST2,
        "DE_MIRAGE" => self::DE_MIRAGE
    ];
}
