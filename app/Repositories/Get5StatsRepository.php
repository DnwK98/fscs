<?php


namespace App\Repositories;


use App\Get5StatsMap;
use App\Get5StatsPlayer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Get5StatsRepository
{
    /**
     * @param $id
     * @return Get5StatsMap|Model|null
     */
    public function getMapByMatchId($id)
    {
        return Get5StatsMap::query()
            ->where(['matchid' => $id])
            ->first();
    }

    /**
     * @param int $id
     * @return Get5StatsPlayer[]
     */
    public function getPlayersByMatchId(int $id)
    {
        return Get5StatsPlayer::query()
            ->where(['matchid' => $id])
            ->getModels();
    }
}
