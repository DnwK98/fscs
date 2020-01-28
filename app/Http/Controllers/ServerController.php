<?php


namespace App\Http\Controllers;


use App\Enums\MapEnum;
use App\Enums\ServerStatusEnum;
use App\Http\Requests\ServerCreateRequest;
use App\Http\Responses\BadRequestResponse;
use App\Http\Responses\CreatedResponse;
use App\Http\Responses\NotFoundResponse;
use App\Http\Responses\ObjectResponse;
use App\Http\Responses\OkResponse;
use App\Services\Server\Dto\ServerDto;
use App\Services\Server\ServerService;
use Illuminate\Validation\ValidationException;

class ServerController
{
    /** @var ServerService */
    protected $serverService;

    public function __construct(ServerService $serverService)
    {
        $this->serverService = $serverService;
    }

    public function options()
    {
        return new ObjectResponse([
            'GET' => [],
            'POST' => [],
        ]);
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

    public function post(ServerCreateRequest $request)
    {
        $requestBody = json_decode($request->getContent(), true);
        if(isset($requestBody['id']) && $this->serverService->getServerById($requestBody['id'])){
            return new BadRequestResponse(['id' => 'There is other server with provided id']);
        }
        if(!MapEnum::Key($requestBody['map'])){
            return new BadRequestResponse(['map' => 'Is not valid map name']);
        }
        $server = $this->serverService->createServer($requestBody);

        return new CreatedResponse(['id' => $server->id]);
    }

    public function restart(int $id)
    {
        $server = $this->serverService->getServerById($id);
        if(!$server instanceof ServerDto){
            return new BadRequestResponse(['id' => 'There is no server']);
        }

        if (!in_array($server->status, [
            ServerStatusEnum::CREATED,
            ServerStatusEnum::STARTED,
            ServerStatusEnum::RESTARTED,
            ServerStatusEnum::PLAY,
        ])) {
            return new BadRequestResponse(['id' => 'This server canno\'t be restarted']);
        }

        $server->status = ServerStatusEnum::RESTARTED;
        $server->save();

        return new OkResponse();
    }
}
