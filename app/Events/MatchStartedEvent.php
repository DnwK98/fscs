<?php


namespace App\Events;


class MatchStartedEvent extends Event
{
    public function __construct(int $serverId)
    {
        $this->boot();
        $this->array['name'] = "MatchStartedEvent";
        $this->array['serverId'] = $serverId;
    }

    public function fieldsMap(): array
    {
        return [
            'int_index' => 'serverId',
        ];
    }
}
