<?php

namespace Elavon\Converge2\Request\Payload;

use Elavon\Converge2\DataObject\C2ApiFieldName;

class BillingIntervalDataBuilder extends AbstractDataBuilder
{

    public function setTimeUnit($value)
    {
        $this->setField(C2ApiFieldName::TIME_UNIT, $value);
    }

    public function setCount($value)
    {
        $this->setField(C2ApiFieldName::COUNT, $value);
    }
}
