<?php


namespace App\Utils;

use Illuminate\Database\Eloquent\Builder as Query;
use Illuminate\Pagination\LengthAwarePaginator;
use Iterator;

class ModelsIterator implements Iterator
{

    /** @var null */
    private $current = null;

    /** @var null */
    private $lastLoaded = null;

    /** @var Query */
    private $query;

    /** @var string  */
    private $field;

    /** @var int */
    private $chunkSize;

    /** @var array */
    private $data = [];

    public function __construct(Query $query, string $distinctOrderField = 'id', int $chunkSize = 200)
    {
        // Chunk size equal one creates infinity loop while checking if is selected last loaded key
        if($chunkSize < 2) {
            $chunkSize = 2;
        }

        $this->query = clone $query;
        $this->field = $distinctOrderField;
        $this->chunkSize = $chunkSize;
    }

    /**
     * @inheritDoc
     */
    public function current()
    {
        return $this->data[$this->current];
    }

    /**
     * @inheritDoc
     */
    public function next()
    {
        if($this->current === $this->lastLoaded){
            $this->loadChunk();
            $this->current = array_key_first($this->data);
        } else {
            $this->current = $this->arrayNextKey($this->data, $this->current);
        }
    }

    /**
     * @inheritDoc
     */
    public function key()
    {
        return $this->current;
    }

    /**
     * @inheritDoc
     */
    public function valid()
    {
        return array_key_exists($this->current, $this->data);
    }

    /**
     * @inheritDoc
     */
    public function rewind()
    {
        $this->lastLoaded = null;
        $this->loadChunk();
        $this->current = array_key_first($this->data);
    }

    private function loadChunk()
    {
        $field = $this->field;
        $query = clone $this->query;
        $query->orderBy($field, 'asc');

        if(isset($this->lastLoaded)){
            $query->where($field, '>', $this->lastLoaded);
        }

        /** @var LengthAwarePaginator $pagination */
        $pagination = $query->paginate($this->chunkSize, ['*'], 'page', 1);
        $models = $pagination->getCollection()->all();

        if(empty($models)){
            $this->data = [];
            return false;
        }

        $this->data = [];
        foreach ($models as $model) {
            $this->data[$model->$field] = $model;
        }

        $this->lastLoaded = end($models)->$field;
        return true;
    }

    /**
     * @param array $data
     * @param string $current
     * @return int|string|null
     */
    private function arrayNextKey(array $data, string $current)
    {
        $found = false;
        foreach ($data as $key => $value){
            if($found){
                return $key;
            }
            if((string)$key === (string)$current){
                $found = true;
            }
        }
        return null;
    }
}
