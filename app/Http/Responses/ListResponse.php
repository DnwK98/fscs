<?php

namespace App\Http\Responses;


class ListResponse extends BaseResponse
{
    public function __construct(array $data, $page, $limit, $allCount)
    {
        $this->responseAppend['pagination'] = [
            'page' => $page ?? 1,
            'limit' => $limit,
            'count' => count($data),
            'allCount' => $allCount
        ];

        parent::__construct($data);
    }
}
