<?php

namespace Elavon\Converge2\Request\Payload;

use Elavon\Converge2\DataObject\C2ApiFieldName;
use Elavon\Converge2\Request\Payload\DataSetter\CustomReferenceSetterTrait;
use Elavon\Converge2\Request\Payload\DataSetter\DescriptionSetterTrait;
use Elavon\Converge2\Request\Payload\DataSetter\TotalSetterTrait;
use Elavon\Converge2\Request\Payload\DataSetter\TypeSetterTrait;

class OrderItemDataBuilder extends AbstractDataBuilder
{
    use TotalSetterTrait;
    use TypeSetterTrait;
    use DescriptionSetterTrait;
    use CustomReferenceSetterTrait;

    public function setUnitPriceAmountCurrencyCode($amount, $currency_code)
    {
        $total_builder = new TotalDataBuilder();
        $total_builder->setAmount($amount);
        $total_builder->setCurrencyCode($currency_code);

        $this->setUnitPrice($total_builder->getData());
    }

    public function setUnitPrice($value) {
        $this->setField(C2ApiFieldName::UNIT_PRICE, $value);
    }

    public function setQuantity($value)
    {
        $this->setField(C2ApiFieldName::QUANTITY, $value);
    }
}
