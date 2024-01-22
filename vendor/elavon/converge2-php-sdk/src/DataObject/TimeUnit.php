<?php

namespace Elavon\Converge2\DataObject;

final class TimeUnit extends AbstractEnum
{
    const DAY = 'day';
    const WEEK = 'week';
    const MONTH = 'month';
    const YEAR = 'year';

    public function isDay()
    {
        return self::DAY == $this->getValue();
    }

    public function isWeek()
    {
        return self::WEEK == $this->getValue();
    }

    public function isMonth()
    {
        return self::MONTH == $this->getValue();
    }

    public function isYear()
    {
        return self::YEAR == $this->getValue();
    }
}