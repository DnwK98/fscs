<?php

namespace App\Http\Responses;


class MethodNotAllowedHttpResponse extends BaseResponse
{
    protected $status = 405;
    protected $statusMessage = 'Method not allowed';

    public function __construct($allowedMethods = "")
    {
        if($allowedMethods !== "") {
            $this->metaAppend['allowedMethods'] = $allowedMethods;
        }
        parent::__construct(null);
    }
}