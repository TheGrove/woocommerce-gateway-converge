<?php

namespace Elavon\Converge2\DataObject\DataGetter\Field;

use Elavon\Converge2\DataObject\AmountAndCurrency;
use Elavon\Converge2\DataObject\C2ApiFieldName;

/**
 * @method getDataField($field)
 * @method castToDataObjectClass($field, $class)
 */
trait UnitPriceGetterTrait
{
    /**
     * @return AmountAndCurrency|null
     */
    public function getUnitPrice()
    {
        return $this->getDataField(C2ApiFieldName::UNIT_PRICE);
    }

    /**
     * @return string|null
     */
    public function getUnitPriceAmount()
    {
        $unitPrice = $this->getUnitPrice();
        return isset($unitPrice) ? $unitPrice->getAmount() : null;
    }

    /**
     * @return string|null
     */
    public function getUnitPriceCurrencyCode()
    {
        $unitPrice = $this->getUnitPrice();
        return isset($unitPrice) ? $unitPrice->getCurrencyCode() : null;
    }

    protected function castUnitPrice()
    {
        $this->castToDataObjectClass(C2ApiFieldName::UNIT_PRICE, AmountAndCurrency::class);
    }
}
