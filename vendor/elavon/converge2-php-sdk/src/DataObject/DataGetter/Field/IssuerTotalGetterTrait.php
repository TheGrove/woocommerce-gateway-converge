<?php

namespace Elavon\Converge2\DataObject\DataGetter\Field;

use Elavon\Converge2\DataObject\AmountAndCurrency;
use Elavon\Converge2\DataObject\C2ApiFieldName;

/**
 * @method getDataField($field)
 * @method castToDataObjectClass($field, $class)
 */
trait IssuerTotalGetterTrait
{
    /**
     * @return AmountAndCurrency|null
     */
    public function getIssuerTotal()
    {
        return $this->getDataField(C2ApiFieldName::ISSUER_TOTAL);
    }

    /**
     * @return string|null
     */
    public function getIssuerTotalAmount()
    {
        $issuerTotal = $this->getIssuerTotal();
        return isset($issuerTotal) ? $issuerTotal->getAmount() : null;
    }

    /**
     * @return string|null
     */
    public function getIssuerTotalCurrencyCode()
    {
        $issuerTotal = $this->getIssuerTotal();
        return isset($issuerTotal) ? $issuerTotal->getCurrencyCode() : null;
    }

    protected function castIssuerTotal()
    {
        $this->castToDataObjectClass(C2ApiFieldName::ISSUER_TOTAL, AmountAndCurrency::class);
    }
}
