<?php

namespace App\Http\Responses;

class BadRequestResponse extends BaseResponse
{
    protected $status = 400;
    protected $statusMessage = 'Bad Request';


    public function __construct($validationErrors)
    {
        if(!empty($validationErrors)) {
            $this->responseAppend['validationErrors'] = $validationErrors;
        }

        parent::__construct(null);
    }
}
