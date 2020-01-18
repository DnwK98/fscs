<?php


namespace App\Http\Controllers;


use App\Http\Responses\NotFoundResponse;
use App\Http\Responses\ObjectResponse;
use App\Services\Server\Dto\ServerDto;
use App\Services\Server\ServerService;

class ServerController
{
    /** @var ServerService */
    protected $serverService;

    public function __construct(ServerService $serverService)
    {
        $this->serverService = $serverService;
    }

    public function head()
    {

    }

    public function get(int $id)
    {
        $server = $this->serverService->getServerById($id);
        if(!$server instanceof ServerDto){
            return new NotFoundResponse();
        }

        return new ObjectResponse($server);
    }

    public function getList()
    {

    }

    public function post()
    {
        return new ObjectResponse([]);
    }
}
