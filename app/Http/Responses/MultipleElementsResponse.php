<?php
/**
 * Created by PhpStorm.
 * User: Damian
 * Date: 11.09.2019
 * Time: 18:22
 */

namespace App\Http\Responses;


class MultipleElementsResponse extends BaseResponse
{
    public function __construct(array $data, $page, $limit, $allCount)
    {
        $this->responseAppend['pagination'] = [
            'page' => $page,
            'limit' => $limit,
            'count' => count($data),
            'allCount' => $allCount
        ];
        parent::__construct($data);
    }
}