<?php

namespace App\Http\Requests;

/**
 * @property int page
 * @property int limit
 */
class UserListRequest extends Request
{
    public function rules()
    {
        return [
            'page' => "numeric|min:1",
            'limit' => "numeric|min:1",
            'name' => "string",
            'email' => "string"
        ];
    }
}
