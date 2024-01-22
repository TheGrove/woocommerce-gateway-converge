<?php

namespace Elavon\Converge2\DataObject;

final class AmountAndCurrency extends AbstractDataObject
{
    /**
     * @return string|null
     */
    public function getAmount()
    {
        return $this->getDataField(C2ApiFieldName::AMOUNT);
    }

    /**
     * @return string|null
     */
    public function getCurrencyCode()
    {
        return $this->getDataField(C2ApiFieldName::CURRENCY_CODE);
    }
}
