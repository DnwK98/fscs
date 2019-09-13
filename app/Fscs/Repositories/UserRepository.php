<?php
/**
 * Created by PhpStorm.
 * User: Damian
 * Date: 11.09.2019
 * Time: 18:58
 */

namespace App\Fscs\Repositories;


use App\User;

class UserRepository
{
    public function findUsersByNamePaginate($name, $page, $limit)
    {
        $query = User::query();

        $query->where('name', 'LIKE', '%' . $name . '%');
        $query->limit($limit);
        $query->paginate($limit, ['*'], 'page', $page);

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