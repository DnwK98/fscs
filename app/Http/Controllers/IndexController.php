<?php

namespace App\Http\Controllers;

use App\Http\Middleware\CheckToken;
use App\Http\Responses\ObjectResponse;
use Illuminate\Http\Request;

class IndexController extends Controller
{

    public function index()
    {
        return view('frontend', []);
    }
}
