<?php

namespace Elavon\Converge2\DataObject\DataGetter\Field;

use Elavon\Converge2\DataObject\AmountAndCurrency;
use Elavon\Converge2\DataObject\C2ApiFieldName;

/**
 * @method getDataField($field)
 * @method castToDataObjectClass($field, $class)
 */
trait TotalGetterTrait
{
    /**
     * @return AmountAndCurrency|null
     */
    public function getTotal()
    {
        return $this->getDataField(C2ApiFieldName::TOTAL);
    }

    /**
     * @return string|null
     */
    public function getTotalAmount()
    {
        $total = $this->getTotal();
        return isset($total) ? $total->getAmount() : null;
    }

    /**
     * @return string|null
     */
    public function getTotalCurrencyCode()
    {
        $total = $this->getTotal();
        return isset($total) ? $total->getCurrencyCode() : null;
    }

    protected function castTotal()
    {
        $this->castToDataObjectClass(C2ApiFieldName::TOTAL, AmountAndCurrency::class);
    }
}
