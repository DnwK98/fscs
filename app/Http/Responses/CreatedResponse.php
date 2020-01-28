<?php

namespace App\Http\Responses;


class CreatedResponse extends BaseResponse
{
    protected $status = 201;
    protected $statusMessage = 'Created';

    public function __construct($data)
    {
        parent::__construct($data);
    }
}
