<?php
/**
 * Created by PhpStorm.
 * User: Damian
 * Date: 13.09.2019
 * Time: 19:17
 */

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