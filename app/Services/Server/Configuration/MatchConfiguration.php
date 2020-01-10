<?php


namespace App\Services\Server\Configuration;


use App\Services\Server\Configuration\TeamConfiguration;

class MatchConfiguration
{
    public function setMatchId(int $id): MatchConfiguration
    {
        return $this;
    }

    public function setSideType(string $sideType): MatchConfiguration
    {
        return $this;
    }

    public function setMap(string $mapName): MatchConfiguration
    {
        return $this;
    }

    public function setHostName(string $name): MatchConfiguration
    {
        return $this;
    }

    public function addSpectator(string $steamId64): MatchConfiguration
    {
        return $this;
    }

    public function addTeam(TeamConfiguration $team): MatchConfiguration
    {
        return $this;
    }

    public function generateJson(): string
    {
        return <<<EOF
{
	"matchid": 10004,
	"num_maps": 1,
	"players_per_team": 1,
	"min_players_to_ready": 1,
	"min_spectators_to_ready": 0,
	"skip_veto": true,
	"veto_first": "team1",
	"side_type": "standard",

	"spectators": {
		"players":
		[
		]
	},

	"maplist":
	[
		"de_mirage"
	],

	"favored_percentage_team1": 50,
	"favored_percentage_text": "HLTV Bets",

	"team1": {
		"name": "ProTeam",
		"tag": "Pro",
		"flag": "PL",
		"logo": "PL",
		"players":
		{
			"STEAM_0:0:53590160" : "Damian"
		}
	},

	"team2": {
		"name": "Dupa",
		"tag": "Dupa",
		"flag": "PL",
		"logo": "PL",
		"players":
		{
			"STEAM_0:1:527267964" : "Eryczek"
		}
	},

	"cvars": {
		"hostname": "Match server #1"
	}
}
EOF;

    }
}
