<?php

namespace Elavon\Converge2\DataObject;

final class SubscriptionState extends AbstractEnum
{

    const ACTIVE = 'active';
    const CANCELLED = 'cancelled';
    const COMPLETED = 'completed';
    const PAST_DUE = 'pastDue';
    const UNKNOWN = 'unknown';
    const UNPAID = 'unpaid';

    public function isActive()
    {
        return self::ACTIVE == $this->getValue();
    }

    public function isCancelled()
    {
        return self::CANCELLED == $this->getValue();
    }

    public function isCompleted()
    {
        return self::COMPLETED == $this->getValue();
    }

    public function isPastDue()
    {
        return self::PAST_DUE == $this->getValue();
    }

    public function isUnknown()
    {
        return self::UNKNOWN == $this->getValue();
    }

    public function isUnpaid()
    {
        return self::UNPAID == $this->getValue();
    }
}
