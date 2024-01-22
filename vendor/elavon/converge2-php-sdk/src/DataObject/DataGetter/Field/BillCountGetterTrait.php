<?php

namespace Elavon\Converge2\DataObject\DataGetter\Field;

use Elavon\Converge2\DataObject\C2ApiFieldName;

/**
 * @method getDataField($field)
 */
trait BillCountGetterTrait
{
    /**
     * @return number|null
     */
    public function getBillCount()
    {
        return $this->getDataField(C2ApiFieldName::BILL_COUNT);
    }
}
