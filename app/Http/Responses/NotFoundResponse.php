<?php
/**
 * Created by PhpStorm.
 * User: Damian
 * Date: 11.09.2019
 * Time: 18:22
 */

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