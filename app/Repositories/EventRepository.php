<?php


namespace App\Repositories;


use App\EventEntity;

class EventRepository
{
    public function getEventsPaginate($where, $page, $limit)
    {
        $fields = ['name', 'created'];
        $query = EventEntity::query();

        foreach ($fields as $field){
            if(isset($where[$field])){
                $query->where([$field, $where[$field]]);
            }
        }

        if($limit !== null) {
            $query->limit($limit);
            $query->paginate($limit, ['*'], 'page', $page);
        }

        return $query->getModels();

    }

    public function getEventsCount()
    {
        return EventEntity::count();
    }
}
