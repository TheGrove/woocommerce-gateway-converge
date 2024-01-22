<?php

namespace Elavon\Converge2\Request\Payload\DataSetter;

use Elavon\Converge2\DataObject\C2ApiFieldName;

/**
 * @method setField($field, $value)
 */
trait DebtorAccountSetterTrait
{
    public function setDebtorAccount($value)
    {
        $this->setField(C2ApiFieldName::DEBTOR_ACCOUNT, $value);
    }
}
