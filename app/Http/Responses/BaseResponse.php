<?php
/**
 * Created by PhpStorm.
 * User: Damian
 * Date: 11.09.2019
 * Time: 18:13
 */

namespace App\Http\Responses;




use Illuminate\Http\JsonResponse;

abstract class BaseResponse extends JsonResponse
{

    protected $status = 200;
    protected $statusMessage = 'Ok';
    protected $metaAppend = [];
    protected $responseAppend = [];


    public function __construct($data = null, array $headers = [])
    {
        $responseData = [
            'data' => $data,
            'meta' => $this->prepareResponseMeta(),
        ];

        foreach ($this->responseAppend as $key => $value) {
            $responseData[$key] = $value;
        }

        parent::__construct($responseData, $this->status, $headers, 128);
    }

    private function prepareResponseMeta()
    {
        $meta = [];
        $meta['status'] = $this->status;
        $meta['statusMessage'] = $this->statusMessage;

        foreach ($this->metaAppend as $key => $value) {
            $meta[$key] = $value;
        }

        return $meta;
    }
}