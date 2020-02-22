<?php


namespace App\Http\Controllers;


use App\Events\Event;
use App\Http\Requests\EventListRequest;
use App\Http\Requests\Request;
use App\Http\Responses\ListResponse;
use App\Services\Event\EventAccessService;

class EventController extends Controller
{
    /** @var EventAccessService */
    protected $eventAccess;

    public function __construct(EventAccessService $eventAccess)
    {
        $this->eventAccess = $eventAccess;
    }

    public function getList(EventListRequest $request)
    {
        $events = $this->eventAccess->getEventsPaginate($request->name, $request->page, $request->limit);
        $count = $this->eventAccess->getEventsCount();

        return new ListResponse(
            array_map(function ($event){ /** @var Event $event */return $event->__toArray();}, $events),
            $request->page,
            $request->limit,
            $count
        );
    }
}
