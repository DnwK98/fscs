<?php


namespace App\Services\Server\Configuration;


use App\Enums\MapEnum;
use App\Services\Server\Configuration\TeamConfiguration;

class MatchConfiguration
{
    /** @var int */
    private $matchId = 1;

    /** @var string */
    private $sideType = "standard";

    /** @var string */
    private $map = MapEnum::DE_DUST2;

    /** @var string */
    private $hostName = "FSCS Host";

    /** @var string[] */
    private $spectators = [];

    /** @var TeamConfiguration[] */
    private $teams = [];

    public function setMatchId(int $id): MatchConfiguration
    {
        $this->matchId = $id;
        return $this;
    }

    public function setSideType(string $sideType): MatchConfiguration
    {
        $this->sideType = $sideType;
        return $this;
    }

    public function setMap(string $mapName): MatchConfiguration
    {
        if(in_array($mapName, MapEnum::Map())){
            $this->map = $mapName;
        }
        return $this;
    }

    public function setHostName(string $name): MatchConfiguration
    {
        $this->hostName = $name;
        return $this;
    }

    public function addSpectator(string $steamId64): MatchConfiguration
    {
        $this->spectators[] = $steamId64;
        return $this;
    }

    public function addTeam(TeamConfiguration $team): MatchConfiguration
    {
        if(count($this->teams) <= 2){
            $this->teams[] = $team;
        }

        return $this;
    }

    public function getMatchId()
    {
        return $this->matchId;
    }

    public function generateJson(): string
    {

        $array = [
            "matchid" => $this->matchId,
            "num_maps" => 1,
            "min_players_to_ready" => 1,
            "min_spectators_to_ready" => 0,
            "skip_veto" => true,
            "veto_first" => "team1",
            "side_type" => "standard",
            "spectators" => $this->spectators,
            "maplist" => [$this->map],
            "favored_percentage_team1" => 50,
            "favored_percentage_text" => "I",
            "cvars" => [
                "hostname" => $this->hostName
            ]
        ];
        $i = 0;
        foreach ($this->teams as $team){
            $array["players_per_team"] =  $team->getPlayersCount();
            $array["team" . ++$i] = $team->__toArray();
        }

        return (string)json_encode($array, JSON_PRETTY_PRINT);

    }
}
