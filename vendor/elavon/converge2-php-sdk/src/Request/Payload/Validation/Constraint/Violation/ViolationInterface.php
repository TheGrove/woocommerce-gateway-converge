<?php

namespace Elavon\Converge2\Request\Payload\Validation\Constraint\Violation;

interface ViolationInterface
{
    public function getAsArray();

    public function setMessage($msg);

    public function getFormattedMessage();

    public function getConstraintId();

    public function getConstraintParameterArray();

    public function getConstraintParameter($key = null);

    public function getOffendingValue();

    public function getField();

    public function setField($name);

    public function prefixField($prefix);
}