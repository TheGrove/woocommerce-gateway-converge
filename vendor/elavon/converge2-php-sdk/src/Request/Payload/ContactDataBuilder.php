<?php

namespace Elavon\Converge2\Request\Payload;

use Elavon\Converge2\DataObject\C2ApiFieldName;
use Elavon\Converge2\Request\Payload\DataSetter\PostalCodeSetterTrait;

class ContactDataBuilder extends AbstractDataBuilder
{
    use PostalCodeSetterTrait;

    public function setFullName($value)
    {
        $this->setField(C2ApiFieldName::FULL_NAME, $value);
    }

    public function setCompany($value)
    {
        $this->setField(C2ApiFieldName::COMPANY, $value);
    }

    public function setStreet1($value)
    {
        $this->setField(C2ApiFieldName::STREET_1, $value);
    }

    public function setStreet2($value)
    {
        $this->setField(C2ApiFieldName::STREET_2, $value);
    }

    public function setCity($value)
    {
        $this->setField(C2ApiFieldName::CITY, $value);
    }

    public function setRegion($value)
    {
        $this->setField(C2ApiFieldName::REGION, $value);
    }

    public function setCountryCode($value)
    {
        $this->setField(C2ApiFieldName::COUNTRY_CODE, $value);
    }

    public function setPrimaryPhone($value)
    {
        $this->setField(C2ApiFieldName::PRIMARY_PHONE, $value);
    }

    public function setAlternatePhone($value)
    {
        $this->setField(C2ApiFieldName::ALTERNATE_PHONE, $value);
    }

    public function setFax($value)
    {
        $this->setField(C2ApiFieldName::FAX, $value);
    }

    public function setEmail($value)
    {
        $this->setField(C2ApiFieldName::EMAIL, $value);
    }
}
