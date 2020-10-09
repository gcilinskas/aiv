<?php

namespace App\Response;

/**
 * Class Entity
 *
 * @package App\Response
 */
class Entity implements ResponseData
{
    protected $entity;

    /**
     * for addition array data in api
     *
     * @var array
     */
    protected $additionalData = [];

    /**
     * @return array
     */
    public function getResponseArray(): array
    {
        if (null == $this->entity) {
            return [
                'data' => [],
                'additional' => $this->additionalData,
            ];
        }

        return [
            'data' => $this->entity,
            'additional' => $this->additionalData,
        ];
    }

    /**
     * @param $entity
     *
     * @return Entity
     */
    public function setEntity($entity): Entity
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * @param array $additionalData
     */
    public function setAdditionalData(array $additionalData)
    {
        $this->additionalData = $additionalData;

        return $this;
    }

}
