<?php

namespace Elavon\Converge2\DataObject;

final class BatchState extends AbstractEnum
{
    const SUBMITTED = 'submitted';
    const SETTLED = 'settled';
    const REJECTED = 'rejected';
    const FAILED = 'failed';
    const UNKNOWN = 'unknown';

    public function isSubmitted()
    {
        return self::SUBMITTED == $this->getValue();
    }

    public function isSettled()
    {
        return self::SETTLED == $this->getValue();
    }

    public function isRejected()
    {
        return self::REJECTED == $this->getValue();
    }

    public function isFailed()
    {
        return self::FAILED == $this->getValue();
    }

    public function isUnknown()
    {
        return self::UNKNOWN == $this->getValue();
    }
}