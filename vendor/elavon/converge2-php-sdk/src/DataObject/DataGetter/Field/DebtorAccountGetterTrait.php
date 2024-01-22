<?php

namespace Elavon\Converge2\DataObject\DataGetter\Field;

use Elavon\Converge2\DataObject\C2ApiFieldName;
use Elavon\Converge2\DataObject\DebtorAccount;

/**
 * @method getDataField($field)
 * @method castToDataObjectClass($field, $class)
 */
trait DebtorAccountGetterTrait
{
    /**
     * @return DebtorAccount|null
     */
    public function getDebtorAccount()
    {
        return $this->getDataField(C2ApiFieldName::DEBTOR_ACCOUNT);
    }

    protected function castDebtorAccount()
    {
        $this->castToDataObjectClass(C2ApiFieldName::DEBTOR_ACCOUNT, DebtorAccount::class);
    }
}
