<?php


namespace App\Services\Server\Configuration;


class TeamConfiguration
{
    public function setName(string $name): TeamConfiguration
    {
        return $this;
    }

    public function setTag(string $tag): TeamConfiguration
    {
        return $this;
    }

    public function addPlayer(string $steamId64, string $name): bool
    {
        return true;
    }
}
