<?php


namespace App\Http\Responses;


class UnauthorizedResponse extends BaseResponse
{
    protected $status = 401;
    protected $statusMessage = 'Unauthorized';

    public function __construct()
    {
        parent::__construct(null);
    }
}
