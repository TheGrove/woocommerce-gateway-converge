<?php

namespace Elavon\Converge2\DataObject;

final class RecurringType extends AbstractEnum
{
    const NONE = 'none';
    const FIRST = 'first';
    const SUBSEQUENT = 'subsequent';
    const UNSCHEDULED = 'unscheduled';

    public function isNone()
    {
        return self::NONE == $this->getValue();
    }

    public function isFirst()
    {
        return self::FIRST == $this->getValue();
    }

    public function isSubsequent()
    {
        return self::SUBSEQUENT == $this->getValue();
    }

    public function isUnscheduled()
    {
        return self::UNSCHEDULED == $this->getValue();
    }
}
