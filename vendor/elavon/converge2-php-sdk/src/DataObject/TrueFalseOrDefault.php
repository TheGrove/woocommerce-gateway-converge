<?php

namespace Elavon\Converge2\DataObject;

final class TrueFalseOrDefault extends AbstractEnum
{
    const STRING_TRUE = 'true';
    const STRING_FALSE = 'false';
    const STRING_DEFAULT = 'default';

    public function isTrue()
    {
        return self::STRING_TRUE == $this->getValue();
    }

    public function isFalse()
    {
        return self::STRING_FALSE == $this->getValue();
    }

    public function isDefault()
    {
        return self::STRING_DEFAULT == $this->getValue();
    }
}
