<?php


namespace App\Events;


use App\EventEntity;
use DateTime;


class Event
{
    /** @var array */
    public $array;

    public function fieldsMap(): array
    {
        return [
            'int_index' => null,
            'int_1' => null,
            'int_2' => null,
            'string_index' => null,
            'string_1' => null,
        ];
    }

    protected function boot()
    {
        $this->array = [
            'name' => null,
            'created' => (new DateTime())->format('Y-m-d H:i:s')
        ];
    }

    public function __toArray(): array
    {
        return $this->array;
    }

    public function getName(): string
    {
        if (!isset($this->array['name'])) {
            return static::class;
        }
        return $this->array['name'];
    }

    public function getCreated(): DateTime
    {
        try {
            return new DateTime($this->array['created']);
        } catch (\Exception $e) {
            return new DateTime();
        }
    }
}
