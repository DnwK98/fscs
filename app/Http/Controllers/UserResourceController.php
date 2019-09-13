<?php

namespace App\Http\Controllers;


use App\Fscs\HttpRequests\UserListRequest;
use App\Fscs\HttpRequestValidators\UserListRequestValidator;
use App\Fscs\HttpResponses\BadRequestResponse;
use App\Fscs\HttpResponses\MultipleElementsResponse;
use App\Fscs\HttpResponses\NotFoundResponse;
use App\Fscs\HttpResponses\SingleElementResponse;
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