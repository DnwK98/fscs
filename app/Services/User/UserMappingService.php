<?php


namespace App\Services\User;


use App\Services\User\Dto\UserDto;
use App\User;
use Illuminate\Database\Eloquent\Model;

class UserMappingService
{
    /**
     * @param User|Model $userModel
     * @return UserDto
     */
    public function mapEntityToDto(User $userModel): UserDto
    {
        $userDto = new UserDto();
        $userDto->id = $userModel->id;
        $userDto->name = $userModel->name;
        $userDto->email = $userModel->email;
        return $userDto;
    }

    /**
     * @param User[]|Model[] $users
     * @return UserDto[]
     */
    public function mapEntitiesToDtoArray($users): array
    {
        $usersDto = [];
        foreach ($users as $user) {
            $usersDto[] = $this->mapEntityToDto($user);
        }

        return $usersDto;
    }
}
