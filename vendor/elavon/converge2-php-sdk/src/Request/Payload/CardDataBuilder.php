<?php

namespace Elavon\Converge2\Request\Payload;

use Elavon\Converge2\DataObject\C2ApiFieldName;
use Elavon\Converge2\Request\Payload\DataSetter\BillToSetterTrait;

class CardDataBuilder extends AbstractDataBuilder
{
    use BillToSetterTrait;

    public function setHolderName($value)
    {
        $this->setField(C2ApiFieldName::HOLDER_NAME, $value);
    }

    public function setNumber($value)
    {
        $this->setField(C2ApiFieldName::NUMBER, $value);
    }

    public function setPanToken($value)
    {
        $this->setField(C2ApiFieldName::PAN_TOKEN, $value);
    }

    public function setPanFingerprint($value)
    {
        $this->setField(C2ApiFieldName::PAN_FINGERPRINT, $value);
    }

    public function setExpirationMonth($value)
    {
        $this->setField(C2ApiFieldName::EXPIRATION_MONTH, $value);
    }

    public function setExpirationYear($value)
    {
        $this->setField(C2ApiFieldName::EXPIRATION_YEAR, $value);
    }

    public function setSecurityCode($value)
    {
        $this->setField(C2ApiFieldName::SECURITY_CODE, $value);
    }

    public function setMaskedNumber($value)
    {
        $this->setField(C2ApiFieldName::MASKED_NUMBER, $value);
    }

    public function setLast4($value)
    {
        $this->setField(C2ApiFieldName::LAST_4, $value);
    }

    public function setBin($value)
    {
        $this->setField(C2ApiFieldName::BIN, $value);
    }

    public function setScheme($value)
    {
        $this->setField(C2ApiFieldName::SCHEME, $value);
    }

    public function setBrand($value)
    {
        $this->setField(C2ApiFieldName::BRAND, $value);
    }

    public function setFundingSource($value)
    {
        $this->setField(C2ApiFieldName::FUNDING_SOURCE, $value);
    }

    public function setIssuingBank($value)
    {
        $this->setField(C2ApiFieldName::ISSUING_BANK, $value);
    }

    public function setIssuingCountry($value)
    {
        $this->setField(C2ApiFieldName::ISSUING_COUNTRY, $value);
    }

    public function setIssuingCurrency($value)
    {
        $this->setField(C2ApiFieldName::ISSUING_CURRENCY, $value);
    }

    public function setIsDebit($value)
    {
        $this->setField(C2ApiFieldName::IS_DEBIT, $value);
    }

    public function setIsCorporate($value)
    {
        $this->setField(C2ApiFieldName::IS_CORPORATE, $value);
    }

    public function setIsDccAllowed($value)
    {
        $this->setField(C2ApiFieldName::IS_DCC_ALLOWED, $value);
    }
}
