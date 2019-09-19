<?php

namespace App\Http\Controllers;

use App\Http\RequestValidators\Validator;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    private $validator;

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function getValidator()
    {
        if (!$this instanceof Validator){
            $this->validator = new Validator();
        }
        return $this->validator;
    }
}
