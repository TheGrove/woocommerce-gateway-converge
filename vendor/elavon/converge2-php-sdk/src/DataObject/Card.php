<?php

namespace Elavon\Converge2\DataObject;

use Elavon\Converge2\DataObject\DataGetter\Field\BillToGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\BinGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\Last4GetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\MaskedNumberGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\PanFingerprintGetterTrait;

final class Card extends AbstractDataObject
{
    use PanFingerprintGetterTrait;
    use BillToGetterTrait;
    use MaskedNumberGetterTrait;
    use Last4GetterTrait;
    use BinGetterTrait;

    protected function castObjectFields()
    {
        $this->castPanToken();
        $this->castBillTo();
        $this->castScheme();
        $this->castBrand();
        $this->castFundingSource();
        $this->castIsDebit();
        $this->castIsCorporate();
        $this->castIsDccAllowed();
    }

    public function isDebit()
    {
        $value = $this->getIsDebit();
        return isset($value) && $value->isTrue();
    }

    public function isCorporate()
    {
        $value = $this->getIsCorporate();
        return isset($value) && $value->isTrue();
    }

    public function isDccAllowed()
    {
        $value = $this->getIsDccAllowed();
        return isset($value) && $value->isTrue();
    }

    /**
     * @return string|null
     */
    public function getHolderName()
    {
        return $this->getDataField(C2ApiFieldName::HOLDER_NAME);
    }

    /**
     * @return string|null
     */
    public function getNumber()
    {
        return $this->getDataField(C2ApiFieldName::NUMBER);
    }

    /**
     * @return ValueToken|null
     */
    public function getPanToken()
    {
        return $this->getDataField(C2ApiFieldName::PAN_TOKEN);
    }

    /**
     * @return number|null
     */
    public function getExpirationMonth()
    {
        return $this->getDataField(C2ApiFieldName::EXPIRATION_MONTH);
    }

    /**
     * @return number|null
     */
    public function getExpirationYear()
    {
        return $this->getDataField(C2ApiFieldName::EXPIRATION_YEAR);
    }

    /**
     * @return string|null
     */
    public function getSecurityCode()
    {
        return $this->getDataField(C2ApiFieldName::SECURITY_CODE);
    }

    /**
     * @return CardScheme|null
     */
    public function getScheme()
    {
        return $this->getDataField(C2ApiFieldName::SCHEME);
    }

    /**
     * @return CardBrand|null
     */
    public function getBrand()
    {
        return $this->getDataField(C2ApiFieldName::BRAND);
    }

    /**
     * @return CardFundingSource|null
     */
    public function getFundingSource()
    {
        return $this->getDataField(C2ApiFieldName::FUNDING_SOURCE);
    }

    /**
     * @return string|null
     */
    public function getIssuingBank()
    {
        return $this->getDataField(C2ApiFieldName::ISSUING_BANK);
    }

    /**
     * @return string|null
     */
    public function getIssuingCountry()
    {
        return $this->getDataField(C2ApiFieldName::ISSUING_COUNTRY);
    }

    /**
     * @return string|null
     */
    public function getIssuingCurrency()
    {
        return $this->getDataField(C2ApiFieldName::ISSUING_CURRENCY);
    }

    /**
     * @return TrueFalseOrUnknown|null
     */
    public function getIsDebit()
    {
        return $this->getDataField(C2ApiFieldName::IS_DEBIT);
    }

    /**
     * @return TrueFalseOrUnknown|null
     */
    public function getIsCorporate()
    {
        return $this->getDataField(C2ApiFieldName::IS_CORPORATE);
    }

    /**
     * @return TrueFalseOrUnknown|null
     */
    public function getIsDccAllowed()
    {
        return $this->getDataField(C2ApiFieldName::IS_DCC_ALLOWED);
    }

    protected function castPanToken()
    {
        $this->castToDataObjectClass(C2ApiFieldName::PAN_TOKEN, ValueToken::class);
    }

    protected function castScheme()
    {
        $this->castToDataObjectClass(C2ApiFieldName::SCHEME, CardScheme::class);
    }

    protected function castBrand()
    {
        $this->castToDataObjectClass(C2ApiFieldName::BRAND, CardBrand::class);
    }

    protected function castFundingSource()
    {
        $this->castToDataObjectClass(C2ApiFieldName::FUNDING_SOURCE, CardFundingSource::class);
    }

    protected function castIsDebit()
    {
        $this->castToDataObjectClass(C2ApiFieldName::IS_DEBIT, TrueFalseOrUnknown::class);
    }

    protected function castIsCorporate()
    {
        $this->castToDataObjectClass(C2ApiFieldName::IS_CORPORATE, TrueFalseOrUnknown::class);
    }

    protected function castIsDccAllowed()
    {
        $this->castToDataObjectClass(C2ApiFieldName::IS_DCC_ALLOWED, TrueFalseOrUnknown::class);
    }
}
