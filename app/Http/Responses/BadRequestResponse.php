<?php
/**
 * Created by PhpStorm.
 * User: Damian
 * Date: 11.09.2019
 * Time: 18:22
 */

namespace App\Http\Responses;


use App\Http\RequestValidators\Dto\ValidationError;

class BadRequestResponse extends BaseResponse
{
    protected $status = 400;
    protected $statusMessage = 'Bad Request';


    public function __construct($validationErrors)
    {
        $this->responseAppend['validationErrors'] = $validationErrors;

        parent::__construct(null);
    }
}
