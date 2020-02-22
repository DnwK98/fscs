<?php


namespace App\Http\Controllers;


use App\Http\Responses\ObjectResponse;
use App\Http\Responses\UnauthorizedResponse;
use Illuminate\Http\Request;

class MeController extends Controller
{
    public function get(Request $request)
    {
        $this->authorize();

        return new ObjectResponse("Authorized");
    }
}
