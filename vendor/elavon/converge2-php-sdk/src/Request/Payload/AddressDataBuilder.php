<?php

namespace Elavon\Converge2\Request\Payload;

use Elavon\Converge2\DataObject\C2ApiFieldName;

class AddressDataBuilder extends AbstractDataBuilder
{

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

    public function setPostalCode($value)
    {
        $this->setField(C2ApiFieldName::POSTAL_CODE, $value);
    }

    public function setCountryCode($value)
    {
        $this->setField(C2ApiFieldName::COUNTRY_CODE, $value);
    }
}
