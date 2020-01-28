<?php


namespace App\Services\Event;


use App\Repositories\EventRepository;

class EventAccessService
{
    /** @var EventRepository */
    protected $repository;

    /** @var EventMappingService */
    protected $mappingService;

    public function __construct(EventRepository $repository, EventMappingService $mappingService)
    {
        $this->repository = $repository;
        $this->mappingService = $mappingService;
    }

    public function getEventsPaginate($name = null, $page = 1, $limit = null)
    {
        $entities = $this->repository->getEventsPaginate(
            $name ? ['name' => $name] : [],
            $page,
            $limit
        );

        $events = [];
        foreach ($entities as $entity){
            $events[] = $this->mappingService->mapEntityToEvent($entity);
        }

        return $events;
    }

    public function getEventsCount()
    {
        return $this->repository->getEventsCount();
    }
}
