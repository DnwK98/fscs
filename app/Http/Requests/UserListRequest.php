<?php


namespace App\Http\Requests;


use Illuminate\Http\Request;

class UserListRequest
{
    /** @var int */
    public $page;

    /** @var int */
    public $limit;

    /** @var string */
    public $nameSearch;

    public static function createFromRequest(Request $request)
    {
        return new UserListRequest($request);
    }

    private function __construct(Request $request)
    {
        $this->page = $request->get('page') ?? 1;
        $this->limit = $request->get('limit') ?? 100;
        $this->nameSearch = $request->get('nameSearch') ?? '';
    }
}