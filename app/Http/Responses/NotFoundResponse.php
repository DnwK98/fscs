<?php

namespace App\Http\Responses;


class NotFoundResponse extends BaseResponse
{
    protected $status = 404;
    protected $statusMessage = 'Not Found';

    public function __construct()
    {
        parent::__construct(null);
    }
}