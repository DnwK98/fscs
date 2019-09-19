<?php
/**
 * Created by PhpStorm.
 * User: Damian
 * Date: 11.09.2019
 * Time: 18:22
 */

namespace App\Http\Responses;


class SingleElementResponse extends BaseResponse
{
    public function __construct($data)
    {
        parent::__construct($data);
    }
}