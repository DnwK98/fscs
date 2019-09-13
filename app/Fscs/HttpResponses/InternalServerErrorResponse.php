<?php
/**
 * Created by PhpStorm.
 * User: Damian
 * Date: 11.09.2019
 * Time: 18:22
 */

namespace App\Fscs\HttpResponses;


class InternalServerErrorResponse extends BaseResponse
{
    protected $status = 500;
    protected $statusMessage = 'Internal Server Error';

    public function __construct()
    {
        $this->metaAppend = [
            'debugToken' => bin2hex('fhnaslidfcjnasdklfj'),
        ];

        parent::__construct(null);
    }
}