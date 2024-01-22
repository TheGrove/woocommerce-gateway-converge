<?php

namespace Elavon\Converge2\DataObject;

final class MarkupRateAnnotation extends AbstractEnum
{
    const ABOVE_ECB = 'aboveEcb';
    const BELOW_ECB = 'belowEcb';
    const NONE = 'none';

    public function isAboveEcb()
    {
        return self::ABOVE_ECB == $this->getValue();
    }

    public function isBelowEcb()
    {
        return self::BELOW_ECB == $this->getValue();
    }

    public function isNone()
    {
        return self::NONE == $this->getValue();
    }
}
