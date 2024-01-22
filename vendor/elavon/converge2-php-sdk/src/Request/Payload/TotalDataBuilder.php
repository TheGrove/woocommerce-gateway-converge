<?php

namespace Elavon\Converge2\Request\Payload;

use Elavon\Converge2\DataObject\C2ApiFieldName;

class TotalDataBuilder extends AbstractDataBuilder
{

    public function setAmount($value)
    {
        $this->setField(C2ApiFieldName::AMOUNT, $value);
    }

    public function setCurrencyCode($value)
    {
        $this->setField(C2ApiFieldName::CURRENCY_CODE, $value);
    }
}