<?php


namespace App\Http\Requests;


use App\Http\Requests\Request;

class TokenAuthRequest extends Request
{
    public function rules()
    {
        return [
            'username' => 'string|required',
            'password' => 'string|required',
        ];
    }
}
