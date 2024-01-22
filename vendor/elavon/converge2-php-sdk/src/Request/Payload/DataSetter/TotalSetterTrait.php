<?php

namespace Elavon\Converge2\Request\Payload\DataSetter;

use Elavon\Converge2\DataObject\C2ApiFieldName;
use Elavon\Converge2\Request\Payload\TotalDataBuilder;

trait TotalSetterTrait
{
    public function setTotalAmountCurrencyCode($amount, $currency_code)
    {
        $total_builder = new TotalDataBuilder();
        $total_builder->setAmount($amount);
        $total_builder->setCurrencyCode($currency_code);

        $this->setTotal($total_builder->getData());
    }

    public function setTotal($value) {
        $this->setField(C2ApiFieldName::TOTAL, $value);
    }
}
