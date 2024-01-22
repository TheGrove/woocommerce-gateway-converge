<?php

namespace Elavon\Converge2\Request\Payload;

use Elavon\Converge2\DataObject\C2ApiFieldName;
use Elavon\Converge2\Request\Payload\DataSetter\CustomFieldsSetterTrait;
use Elavon\Converge2\Request\Payload\DataSetter\CustomReferenceSetterTrait;
use Elavon\Converge2\Request\Payload\DataSetter\DescriptionSetterTrait;

class ShopperDataBuilder extends AbstractDataBuilder
{
    use DescriptionSetterTrait;
    use CustomReferenceSetterTrait;
    use CustomFieldsSetterTrait;

    public function setDefaultStoredCard($value)
    {
        $this->setField(C2ApiFieldName::DEFAULT_STORED_CARD, $value);
    }

    public function setFullName($value)
    {
        $this->setField(C2ApiFieldName::FULL_NAME, $value);
    }

    public function setCompany($value)
    {
        $this->setField(C2ApiFieldName::COMPANY, $value);
    }

    public function setPrimaryAddress($value)
    {
        $this->setField(C2ApiFieldName::PRIMARY_ADDRESS, $value);
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
