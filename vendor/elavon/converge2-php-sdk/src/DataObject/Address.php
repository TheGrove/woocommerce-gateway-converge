<?php

namespace Elavon\Converge2\DataObject;

final class Address extends AbstractDataObject
{
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
    public function getPostalCode()
    {
        return $this->getDataField(C2ApiFieldName::POSTAL_CODE);
    }

    /**
     * @return string|null
     */
    public function getCountryCode()
    {
        return $this->getDataField(C2ApiFieldName::COUNTRY_CODE);
    }
}
