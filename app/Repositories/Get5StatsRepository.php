<?php


namespace App\Repositories;


use App\Get5StatsMap;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Get5StatsRepository
{
    /**
     * @param $id
     * @return Get5StatsMap|Model|null
     */
    public function getMapByMatchId($id)
    {
        $query = Get5StatsMap::query();
        $query->where(['matchid' => $id]);
        $query->limit(1);

        $maps = $query->getModels();
        foreach ($maps as $map){
            return $map;
        }
        return null;
    }
}
