<?php

namespace App\Http\Controllers;

use App\Enums\UserPermissionsEnum;
use App\Http\Requests\UserListRequest;
use App\Http\Responses\BadRequestResponse;
use App\Http\Responses\ListResponse;
use App\Http\Responses\NotFoundResponse;
use App\Http\Responses\ObjectResponse;
use App\Services\User\UserService;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function getList(UserListRequest $request)
    {
        $users = $this->userService->getUserListByRequestParameters($request->all());
        $usersCount = $this->userService->getUsersCount();

        return new ListResponse(
            $users,
            $request->page,
            $request->limit,
            $usersCount
        );
    }

    public function get(int $id)
    {
        $user = $this->userService->getUserById($id);

        if($user === null){
            return new NotFoundResponse();
        }

        return new ObjectResponse($user);
    }

}
