<?php

namespace Elavon\Converge2\DataObject;

final class Verification extends AbstractEnum
{
    const MATCHED = 'matched';
    const UNMATCHED = 'unmatched';
    const UNPROVIDED = 'unprovided';
    const UNSUPPORTED = 'unsupported';
    const UNAVAILABLE = 'unavailable';
    const UNKNOWN = 'unknown';

    public function isMatched()
    {
        return self::MATCHED == $this->getValue();
    }

    public function isUnmatched()
    {
        return self::UNMATCHED == $this->getValue();
    }

    public function isUnprovided()
    {
        return self::UNPROVIDED == $this->getValue();
    }

    public function isUnsupported()
    {
        return self::UNSUPPORTED == $this->getValue();
    }

    public function isUnavailable()
    {
        return self::UNAVAILABLE == $this->getValue();
    }

    public function isUnknown()
    {
        return self::UNKNOWN == $this->getValue();
    }
}
