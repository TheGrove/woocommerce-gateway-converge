<?php

namespace Elavon\Converge2\DataObject;

abstract class AbstractDataObject implements \JsonSerializable
{
    use CastToDataObjectTrait;

    /** @var \stdClass */
    protected $data;

    public function __construct(\stdClass $data = null)
    {
        $this->data = clone $data;
        $this->castObjectFields();
    }

    public function jsonSerialize()
    {
        return $this->data;
    }

    protected function getDataField($field)
    {
        return isset($this->data->$field) ? $this->data->$field : null;
    }
}
