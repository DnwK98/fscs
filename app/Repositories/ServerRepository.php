<?php


namespace App\Repositories;


use App\Server;
use App\Utils\ModelsIterator;
use Illuminate\Support\Arr;

class ServerRepository
{
    public function getById($id)
    {
        return ['model'];
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
}
