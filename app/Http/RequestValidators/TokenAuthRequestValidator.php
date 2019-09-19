<?php
/**
 * Created by PhpStorm.
 * User: Damian
 * Date: 11.09.2019
 * Time: 20:14
 */

namespace App\Http\RequestValidators;


use App\Http\Requests\TokenAuthRequest;

class TokenAuthRequestValidator extends Validator
{
    public function validate(TokenAuthRequest $request)
    {
        $this->notEmpty($request->username, 'username');
        $this->notEmpty($request->password, 'password');
        $this->alphanumericSting($request->username, 'username');
        $this->string($request->password, 'password');
    }
}