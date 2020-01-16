<?php


namespace App\Services\Server\Configuration;


class TeamConfiguration
{
    private $name;
    private $tag;
    private $players = [];

    public function setName(string $name): TeamConfiguration
    {
        $this->name = $name;
        return $this;
    }

    public function setTag(string $tag): TeamConfiguration
    {
        $this->tag = $tag;
        return $this;
    }

    public function addPlayer(string $steamId64, string $name): TeamConfiguration
    {
        $this->players[$steamId64] = $name;
        return $this;
    }

    /**
     * @return int
     */
    public function getPlayersCount()
    {
        return count($this->players);
    }

    /**
     * @return array
     */
    public function __toArray()
    {
        return [
            'name' => $this->name,
            'tag' => $this->tag,
            'flag' => 'PL',
            'logo' => 'PL',
            'players' => $this->players
        ];
    }
}
