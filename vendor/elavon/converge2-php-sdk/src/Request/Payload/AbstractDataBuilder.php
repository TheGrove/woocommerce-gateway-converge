<?php

namespace Elavon\Converge2\Request\Payload;

abstract class AbstractDataBuilder
{
    /** @var \stdClass */
    protected $data;

    public function __construct()
    {
        $this->data = new \stdClass();
    }

    public function setData(\stdClass $data)
    {
        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getDataAsArrayAssoc()
    {
        return (array)$this->data;
    }

    protected function setField($field, $value)
    {
        $this->data->$field = $value;
    }

    public function __clone()
    {
        $this->data = clone $this->data;
    }
}