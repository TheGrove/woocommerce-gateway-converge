<?php

namespace Elavon\Converge2\DataObject\DataGetter\Field;

use Elavon\Converge2\DataObject\AmountAndCurrency;
use Elavon\Converge2\DataObject\C2ApiFieldName;

/**
 * @method getDataField($field)
 * @method castToDataObjectClass($field, $class)
 */
trait InitialTotalGetterTrait
{
    /**
     * @return AmountAndCurrency|null
     */
    public function getInitialTotal()
    {
        return $this->getDataField(C2ApiFieldName::INITIAL_TOTAL);
    }

    /**
     * @return string|null
     */
    public function getInitialTotalAmount()
    {
        $initialTotal = $this->getInitialTotal();
        return isset($initialTotal) ? $initialTotal->getAmount() : null;
    }

    /**
     * @return string|null
     */
    public function getInitialTotalCurrencyCode()
    {
        $initialTotal = $this->getInitialTotal();
        return isset($initialTotal) ? $initialTotal->getCurrencyCode() : null;
    }

    protected function castInitialTotal()
    {
        $this->castToDataObjectClass(C2ApiFieldName::INITIAL_TOTAL, AmountAndCurrency::class);
    }
}
