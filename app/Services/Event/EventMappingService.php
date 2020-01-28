<?php


namespace App\Services\Event;


use App\EventEntity;
use App\Events\Event;
use Illuminate\Database\Eloquent\Model;

class EventMappingService
{
    /**
     * @param Event $event
     * @return EventEntity
     * @throws \Exception
     */
    public function mapEventToEntity($event): EventEntity
    {
        $mapping = array_intersect_key($event->fieldsMap(), array_flip([
            'int_index', 'int_1', 'int_2', 'string_index', 'string_1'
        ]));

        $eventArray = $event->__toArray();

        $eventEntity = new EventEntity();
        $eventEntity->name = $event->getName();
        $eventEntity->created = $event->getCreated();
        $eventEntity->content = json_encode($eventArray);

        foreach ($mapping as $field => $valueName) {
            if (!empty($valueName)) {
                $eventEntity->$field = $eventArray[$valueName];
            }
        }
        return $eventEntity;
    }

    /**
     * @param EventEntity|Model $entity
     * @return Event|null
     */
    public function mapEntityToEvent(EventEntity $entity)
    {
        $class = "App/Events/{$entity->name}";
        if (!class_exists($class)) {
            $event = new Event();
            $event->array = json_decode($entity->content, true);

            return $event;
        }

        /** @var Event $event */
        $event = new $class;
        $event->array = json_decode($entity->content, true);

        return $event;
    }
}
