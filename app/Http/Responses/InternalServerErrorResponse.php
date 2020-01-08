<?php

namespace App\Http\Responses;

class InternalServerErrorResponse extends BaseResponse
{
    protected $status = 500;
    protected $statusMessage = 'Internal Server Error';

    public function __construct($status, $debugToken = null)
    {
        $this->status = $status;

        if($debugToken) {
            $this->metaAppend = [
                'debugToken' => $debugToken
            ];
        }

        parent::__construct(null);
    }
}
