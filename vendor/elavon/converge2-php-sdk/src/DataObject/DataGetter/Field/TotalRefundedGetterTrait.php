<?php

namespace Elavon\Converge2\DataObject\DataGetter\Field;

use Elavon\Converge2\DataObject\AmountAndCurrency;
use Elavon\Converge2\DataObject\C2ApiFieldName;

/**
 * @method getDataField($field)
 * @method castToDataObjectClass($field, $class)
 */
trait TotalRefundedGetterTrait
{
    /**
     * @return AmountAndCurrency|null
     */
    public function getTotalRefunded()
    {
        return $this->getDataField(C2ApiFieldName::TOTAL_REFUNDED);
    }

    /**
     * @return string|null
     */
    public function getTotalRefundedAmount()
    {
        $totalRefunded = $this->getTotalRefunded();
        return isset($totalRefunded) ? $totalRefunded->getAmount() : null;
    }

    /**
     * @return string|null
     */
    public function getTotalRefundedCurrencyCode()
    {
        $totalRefunded = $this->getTotalRefunded();
        return isset($totalRefunded) ? $totalRefunded->getCurrencyCode() : null;
    }

    protected function castTotalRefunded()
    {
        $this->castToDataObjectClass(C2ApiFieldName::TOTAL_REFUNDED, AmountAndCurrency::class);
    }
}