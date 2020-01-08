<?php


namespace App\Http\Controllers;


use App\Http\Responses\ObjectResponse;
use App\Http\Responses\UnauthorizedResponse;
use Illuminate\Http\Request;

class MeController extends Controller
{
    public function get(Request $request)
    {
        if($user = $request->user()) {
            return new ObjectResponse($user);
        } else {
            return new UnauthorizedResponse();
        }
    }
}
