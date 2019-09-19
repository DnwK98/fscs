<?php

namespace App\Http\Controllers;


use App\Http\Requests\UserListRequest;
use App\Http\RequestValidators\UserListRequestValidator;
use App\Http\Responses\BadRequestResponse;
use App\Http\Responses\MultipleElementsResponse;
use App\Http\Responses\NotFoundResponse;
use App\Http\Responses\SingleElementResponse;
use App\Fscs\Services\User\UserService;
use Illuminate\Http\Request;

class UserResourceController extends Controller
{
    protected $userService;
    protected $userListRequestValidator;

    public function __construct(UserService $userService, UserListRequestValidator $userListRequestValidator)
    {
        $this->userService = $userService;
        $this->userListRequestValidator = $userListRequestValidator;

        $this->middleware('auth:api');
    }

    public function getList(Request $request)
    {
        $userListRequest = UserListRequest::createFromRequest($request);

        $this->userListRequestValidator->validate($userListRequest);
        if(!$this->userListRequestValidator->validationPassed()){
            return new BadRequestResponse($this->userListRequestValidator->getValidationErrors());
        }

        $users = $this->userService->getUserListByUserListRequest($userListRequest);
        $usersCount = $this->userService->getUsersCount();

        return new MultipleElementsResponse(
            $users,
            $userListRequest->page,
            $userListRequest->limit,
            $usersCount
        );
    }

    public function get($id)
    {
        $validator = $this->getValidator();
        $validator->positiveInt($id, 'id');
        if(!$validator->validationPassed()){
            return new BadRequestResponse($validator->getValidationErrors());
        }

        $user = $this->userService->getUserById($id);

        if($user === null){
            return new NotFoundResponse();
        }

        return new SingleElementResponse($user);
    }

}