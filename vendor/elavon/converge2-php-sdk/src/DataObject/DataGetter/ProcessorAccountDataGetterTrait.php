<?php

namespace Elavon\Converge2\DataObject\DataGetter;

use Elavon\Converge2\DataObject\Address;
use Elavon\Converge2\DataObject\C2ApiFieldName;
use Elavon\Converge2\DataObject\CardBrand;
use Elavon\Converge2\DataObject\DataGetter\Field\HrefGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\IdGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\MerchantGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\ProcessorReferenceGetterTrait;

/**
 * @method getDataField($field)
 * @method castToDataObjectClass($field, $class)
 */
trait ProcessorAccountDataGetterTrait
{
    use HrefGetterTrait;
    use IdGetterTrait;
    use MerchantGetterTrait;
    use ProcessorReferenceGetterTrait;

    protected function castObjectFields()
    {
        $this->castBusinessAddress();
        $this->castSupportedCardBrands();
    }

    /**
     * @return string|null
     */
    public function getTradeName()
    {
        return $this->getDataField(C2ApiFieldName::TRADE_NAME);
    }

    /**
     * @return Address|null
     */
    public function getBusinessAddress()
    {
        return $this->getDataField(C2ApiFieldName::BUSINESS_ADDRESS);
    }

    /**
     * @return string|null
     */
    public function getBusinessPhone()
    {
        return $this->getDataField(C2ApiFieldName::BUSINESS_PHONE);
    }

    /**
     * @return string|null
     */
    public function getBusinessEmail()
    {
        return $this->getDataField(C2ApiFieldName::BUSINESS_EMAIL);
    }

    /**
     * @return string|null
     */
    public function getBusinessWebsite()
    {
        return $this->getDataField(C2ApiFieldName::BUSINESS_WEBSITE);
    }

    /**
     * @return string|null
     */
    public function getMerchantCategoryCode()
    {
        return $this->getDataField(C2ApiFieldName::MERCHANT_CATEGORY_CODE);
    }

    /**
     * @return string|null
     */
    public function getSettlementCurrencyCode()
    {
        return $this->getDataField(C2ApiFieldName::SETTLEMENT_CURRENCY_CODE);
    }

    /**
     * @return array|null
     */
    public function getSupportedCardBrands()
    {
        return $this->getDataField(C2ApiFieldName::SUPPORTED_CARD_BRANDS);
    }

    protected function castBusinessAddress()
    {
        $this->castToDataObjectClass(C2ApiFieldName::BUSINESS_ADDRESS, Address::class);
    }

    protected function castSupportedCardBrands()
    {
        $this->castToDataObjectClass(C2ApiFieldName::SUPPORTED_CARD_BRANDS, CardBrand::class);
    }
}
