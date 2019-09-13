<?php
/**
 * Created by PhpStorm.
 * User: Damian
 * Date: 11.09.2019
 * Time: 20:14
 */

namespace App\Fscs\HttpRequestValidators;


use App\Fscs\HttpRequests\UserListRequest;

class UserListRequestValidator extends Validator
{
    public function validate(UserListRequest $request)
    {
        $this->positiveInt($request->page, 'page');
        $this->positiveInt($request->limit, 'limit');
        $this->alphanumericSting($request->nameSearch, 'nameSearch');
    }
}