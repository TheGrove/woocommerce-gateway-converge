<?php

namespace Elavon\Converge2\DataObject;

use Elavon\Converge2\DataObject\DataGetter\Field\PostalCodeGetterTrait;

final class Contact extends AbstractDataObject
{
    use PostalCodeGetterTrait;

    /**
     * @return string|null
     */
    public function getFullName()
    {
        return $this->getDataField(C2ApiFieldName::FULL_NAME);
    }

    /**
     * @return string|null
     */
    public function getCompany()
    {
        return $this->getDataField(C2ApiFieldName::COMPANY);
    }

    /**
     * @return string|null
     */
    public function getStreet1()
    {
        return $this->getDataField(C2ApiFieldName::STREET_1);
    }

    /**
     * @return string|null
     */
    public function getStreet2()
    {
        return $this->getDataField(C2ApiFieldName::STREET_2);
    }

    /**
     * @return string|null
     */
    public function getCity()
    {
        return $this->getDataField(C2ApiFieldName::CITY);
    }

    /**
     * @return string|null
     */
    public function getRegion()
    {
        return $this->getDataField(C2ApiFieldName::REGION);
    }

    /**
     * @return string|null
     */
    public function getCountryCode()
    {
        return $this->getDataField(C2ApiFieldName::COUNTRY_CODE);
    }

    /**
     * @return string|null
     */
    public function getPrimaryPhone()
    {
        return $this->getDataField(C2ApiFieldName::PRIMARY_PHONE);
    }

    /**
     * @return string|null
     */
    public function getAlternatePhone()
    {
        return $this->getDataField(C2ApiFieldName::ALTERNATE_PHONE);
    }

    /**
     * @return string|null
     */
    public function getFax()
    {
        return $this->getDataField(C2ApiFieldName::FAX);
    }

    /**
     * @return string|null
     */
    public function getEmail()
    {
        return $this->getDataField(C2ApiFieldName::EMAIL);
    }
}
