<?php

namespace Elavon\Converge2\DataObject;

use Elavon\Converge2\DataObject\DataGetter\Field\PostalCodeGetterTrait;

final class DebtorAccount extends AbstractDataObject
{
    use PostalCodeGetterTrait;

    /**
     * @return string|null
     */
    public function getDateOfBirth()
    {
        return $this->getDataField(C2ApiFieldName::DATE_OF_BIRTH);
    }

    /**
     * @return string|null
     */
    public function getAccountNumber()
    {
        return $this->getDataField(C2ApiFieldName::ACCOUNT_NUMBER);
    }

    /**
     * @return string|null
     */
    public function getLastName()
    {
        return $this->getDataField(C2ApiFieldName::LAST_NAME);
    }
}
