<?php


namespace App\Services\Event;


use App\EventEntity;
use App\Events\Event;
use App\Services\Log\Log;

class EventPropagationService
{
    /** @var Log */
    protected $log;

    /** @var EventMappingService */
    protected $mappingService;


    public function __construct(Log $log, EventMappingService $mappingService)
    {
        $this->log = $log;
        $this->mappingService = $mappingService;
    }

    /**
     * @param Event $event
     * @return bool
     */
    public function propagate($event)
    {
        try {
            $eventEntity = $this->mappingService->mapEventToEntity($event);
            $eventEntity->save();

            return true;
        } catch (\Exception $e) {
            $this->log->error(__METHOD__ . " $e");
            return false;
        }
    }
}
