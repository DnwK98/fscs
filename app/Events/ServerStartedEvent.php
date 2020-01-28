<?php


namespace App\Events;


class ServerStartedEvent extends Event
{

    public function __construct(int $serverId)
    {
        $this->boot();
        $this->array['name'] = "ServerStartedEvent";
        $this->array['serverId'] = $serverId;
    }

    public function fieldsMap(): array
    {
        return [
            'int_index' => 'serverId',
        ];
    }
}
