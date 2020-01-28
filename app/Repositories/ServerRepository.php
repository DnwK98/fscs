<?php


namespace App\Repositories;


use App\Server;
use App\Utils\ModelsIterator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class ServerRepository
{
    /**
     * @param $id
     * @return Server|Model|null
     */
    public function getById($id)
    {
        $query = Server::query();
        $query->where(['id' => $id]);
        $query->limit(1);
        $query->with([
            "teams",
            "teams.players"
        ]);

        return $query->first();
    }

    /**
     * @param array|string $status
     * @return Server[]|ModelsIterator
     */
    public function getAllByStatus($status)
    {
        $status = Arr::wrap($status);

        $query = Server::query();
        $query->whereIn('status', $status);
        $query->with([
            "teams",
            "teams.players"
        ]);

        return new ModelsIterator($query);
    }
    /**
     * @param array|string $status
     * @return int
     */
    public function getCountByStatus($status)
    {
        $status = Arr::wrap($status);

        $query = Server::query();
        $query->whereIn('status', $status);
        return $query->count();
    }

    public function save(Server $server)
    {
        $server->save();
        $server->teams()->saveMany($server->teams);
        foreach ($server->teams as $team){
            $team->players()->saveMany($team->players);
        }
        return true;
    }
}
