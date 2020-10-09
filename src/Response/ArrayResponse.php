<?php

namespace App\Response;

/**
 * Class ArrayResponse
 */
class ArrayResponse implements ResponseData
{
    /** @var array */
    protected $entities;

    /**
     * @return array
     */
    public function getResponseArray(): array
    {
        return ['data' => array_values($this->entities)];
    }

    /**
     * @param array $entities
     * @return ArrayResponse
     */
    public function setEntities(array $entities): ArrayResponse
    {
        $this->entities = $entities;

        return $this;
    }
}
