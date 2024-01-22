<?php

namespace Elavon\Converge2\Request\Payload;

use Elavon\Converge2\DataObject\C2ApiFieldName;
use Elavon\Converge2\Request\Payload\DataSetter\PostalCodeSetterTrait;

class DebtorAccountDataBuilder extends AbstractDataBuilder
{
    use PostalCodeSetterTrait;

    public function setDateOfBirth($value)
    {
        $this->setField(C2ApiFieldName::DATE_OF_BIRTH, $value);
    }

    public function setAccountNumber($value)
    {
        $this->setField(C2ApiFieldName::ACCOUNT_NUMBER, $value);
    }

    public function setLastName($value)
    {
        $this->setField(C2ApiFieldName::LAST_NAME, $value);
    }
}
