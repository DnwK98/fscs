<?php


namespace App\Events;


class MatchFinishedEvent extends Event
{
    public function __construct(int $serverId)
    {
        $this->boot();
        $this->array['name'] = "MatchFinishedEvent";
        $this->array['serverId'] = $serverId;
    }

    public function fieldsMap(): array
    {
        return [
            'int_index' => 'serverId',
        ];
    }
}
