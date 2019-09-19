<?php


namespace App\Http\Requests;


use Illuminate\Http\Request;

class TokenAuthRequest
{
    /** @var string */
    public $username;

    /** @var string */
    public $password;


    public static function createFromRequest(Request $request)
    {
        return new TokenAuthRequest($request);
    }

    private function __construct(Request $request)
    {
        $this->username = $request->get('username') ?? "";
        $this->password = $request->get('password') ?? "";
    }
}