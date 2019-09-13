<?php
/**
 * Created by PhpStorm.
 * User: Damian
 * Date: 11.09.2019
 * Time: 18:51
 */

namespace App\Fscs\Services\User;


use App\Fscs\HttpRequests\UserListRequest;
use App\Fscs\Repositories\UserRepository;
use App\Fscs\Services\User\Dto\UserDto;
use App\User;

class UserService
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param UserListRequest $request
     * @return UserDto[]
     */
    public function getUserListByUserListRequest(UserListRequest $request)
    {
        $userModels = $this->userRepository->findUsersByNamePaginate(
            $request->nameSearch,
            $request->page,
            $request->limit
        );

        $users = [];
        foreach ($userModels as $userModel) {
            $users[] = $this->mapEntityToDto($userModel);
            $userModel = null;
        }
        return $users;
    }

    /**
     * @return int
     */
    public function getUsersCount()
    {
        return  $this->userRepository->getUsersCount();
    }


    /**
     * @param $id
     * @return UserDto|null
     */
    public function getUserById($id)
    {
        $userModel = $this->userRepository->getUserById($id);

        if($userModel){
            return $this->mapEntityToDto($userModel);
        } else {
            return null;
        }
    }

    /**
     * @param User $userModel
     * @return UserDto
     */
    protected function mapEntityToDto(User $userModel)
    {
        $userDto = new UserDto();
        $userDto->id = $userModel->id;
        $userDto->name = $userModel->name;
        $userDto->email = $userModel->email;
        return $userDto;
    }
}