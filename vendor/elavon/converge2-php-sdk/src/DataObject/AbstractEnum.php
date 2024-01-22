<?php

namespace Elavon\Converge2\DataObject;

abstract class AbstractEnum implements \JsonSerializable {
    protected $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function getValue() {
        return $this->value;
    }

    public function jsonSerialize()
    {
        return $this->getValue();
    }

    public function __toString()
    {
        return (string) $this->getValue();
    }
}
