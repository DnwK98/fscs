<?php


namespace App\Enums;


abstract class Enum
{
    protected static $map = [];

    public static function Value($key)
    {
        $map = static::$map;
        return isset($map[$key]) ? $map[$key] : null;
    }

    public static function Key($value)
    {
        $map = static::$map;
        foreach ($map as $key => $val){
            if ($value === $val){
                return $key;
            }
        }
        return null;
    }

    public static function Map()
    {
        return static::$map;
    }

    public static function Random()
    {
        $map = static::$map;
        return $map[array_rand($map)];
    }
}
