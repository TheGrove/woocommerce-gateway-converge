<?php

namespace Elavon\Converge2\DataObject;

final class TrueFalseOrUnknown extends AbstractEnum
{
    const STRING_TRUE = 'true';
    const STRING_FALSE = 'false';
    const STRING_UNKNOWN = 'unknown';

    public function isTrue()
    {
        return self::STRING_TRUE == $this->getValue();
    }

    public function isFalse()
    {
        return self::STRING_FALSE == $this->getValue();
    }

    public function isUnknown()
    {
        return self::STRING_UNKNOWN == $this->getValue();
    }
}
