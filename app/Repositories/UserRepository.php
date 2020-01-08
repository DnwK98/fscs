<?php

namespace App\Repositories;


use App\User;
use Illuminate\Database\Eloquent\Model;

class UserRepository
{
    /**
     * @param $page
     * @param $limit
     * @param array $searchArray
     * @param array $order
     * @return User[]|Model[]
     */
    public function findUsersPaginate($page, $limit, $searchArray = [], $order = [])
    {
        $searchFields = ['name', 'email'];
        $query = User::query();

        foreach ($searchFields as $field){
            if(isset($searchArray[$field])){
                $query->where($field, 'LIKE', '%' . $searchArray[$field] . '%');
            }
        }

        foreach ($order as $field => $destination){
            if(in_array($field, $searchFields)) {
                $query->orderBy($field, $destination);
            }
        }

        if($limit !== null) {
            $query->limit($limit);
            $query->paginate($limit, ['*'], 'page', $page);
        }

        return $query->getModels();
    }

    public function getUsersCount()
    {
        return User::count();
    }

    public function getUserById($id)
    {
        return User::find($id);
    }
}
