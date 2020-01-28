<?php


namespace App\Events;


class ServerRestartedEvent extends Event
{

    public function __construct(int $serverId)
    {
        $this->boot();
        $this->array['name'] = "ServerRestartedEvent";
        $this->array['serverId'] = $serverId;
    }

    public function fieldsMap(): array
    {
        return [
            'int_index' => 'serverId',
        ];
    }
}
