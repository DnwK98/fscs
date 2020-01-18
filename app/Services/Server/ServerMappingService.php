<?php


namespace App\Services\Server;


use App\Get5StatsMap;
use App\Get5StatsPlayer;
use App\Server;
use App\ServerTeam;
use App\Services\Server\Dto\PlayerDto;
use App\Services\Server\Dto\ServerDto;
use App\Services\Server\Dto\TeamDto;
use App\TeamPlayer;

class ServerMappingService
{

    /**
     * @param Server $server
     * @param Get5StatsMap|null $get5Map
     * @param Get5StatsPlayer[] $get5Players
     * @return ServerDto
     */
    public function mapServerToDtoWithStatistics(Server $server, ?Get5StatsMap $get5Map = null, array $get5Players = []): ServerDto
    {
        $dto = new ServerDto();
        $dto->id = $server->id;
        $dto->status = $server->status;
        $dto->map = $server->map;
        $dto->port = $server->port;

        foreach ($server->teams as $team){
            $dto->teams[] = $this->mapTeamToDto($team, $get5Map, $get5Players);
        }

        return $dto;
    }

    /**
     * @param ServerTeam $team
     * @param Get5StatsMap|null $get5Map
     * @param Get5StatsPlayer[] $get5Players
     * @return TeamDto
     */
    public function mapTeamToDto(ServerTeam $team, ?Get5StatsMap $get5Map = null, array $get5Players = []): TeamDto
    {
        $teamDto = new TeamDto();
        $teamDto->name = $team->name;
        $teamDto->tag = $team->tag;
        $teamScoreField = "team{$team->team_number}_score";
        $teamDto->score = $get5Map ? $get5Map->$teamScoreField : null;

        foreach ($team->players as $player) {
            $teamDto->players[] = $this->mapPlayerToDto($player, $get5Players);
        }

        return $teamDto;
    }

    /**
     * @param TeamPlayer $player
     * @param array $get5Players
     * @return PlayerDto
     */
    public function mapPlayerToDto(TeamPlayer $player, array $get5Players = []): PlayerDto
    {
        $dto = new PlayerDto();
        $dto->steamId64 = $player->steam_id_64;
        $dto->name = $player->name;
        $dto->kills = $this->getStatisticForPlayerFromGet5($player->steam_id_64, 'kills', $get5Players);
        $dto->deaths = $this->getStatisticForPlayerFromGet5($player->steam_id_64, 'deaths', $get5Players);
        $dto->assists = $this->getStatisticForPlayerFromGet5($player->steam_id_64, 'assists', $get5Players);
        $dto->headShots = $this->getStatisticForPlayerFromGet5($player->steam_id_64, 'headshot_kills', $get5Players);
        $dto->damageDealt = $this->getStatisticForPlayerFromGet5($player->steam_id_64, 'damage', $get5Players);

        return $dto;
    }

    /**
     * @param string $steamId64
     * @param string $statistic
     * @param Get5StatsPlayer[] $get5Players
     * @return int
     */
    private function getStatisticForPlayerFromGet5(string $steamId64, string $statistic, array $get5Players): ?int
    {
        foreach ($get5Players as $get5Player){
            if($get5Player->steamid64 === $steamId64){
                return $get5Player->$statistic;
            }
        }
        return null;
    }
}
