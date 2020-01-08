<?php

namespace App\Services\User;

use App\Repositories\UserRepository;
use App\Services\User\Dto\UserDto;

class UserService
{
    private $userRepository;
    private $mappingService;

    public function __construct(UserRepository $userRepository, UserMappingService $mappingService)
    {
        $this->userRepository = $userRepository;
        $this->mappingService = $mappingService;
    }

    /**
     * @param array $parameters
     * @return UserDto[]
     */
    public function getUserListByRequestParameters(array $parameters) : array
    {
        $users =  $this->userRepository->findUsersPaginate(
            isset ($parameters['page']) ? $parameters['page'] : 1,
            isset ($parameters['limit']) ? $parameters['limit'] : null,
            $parameters
        );

        return $this->mappingService->mapEntitiesToDtoArray($users);
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
    public function getUserById($id): UserDto
    {
        $userModel = $this->userRepository->getUserById($id);

        if($userModel){
            return $this->mappingService->mapEntityToDto($userModel);
        } else {
            return null;
        }
    }
}
