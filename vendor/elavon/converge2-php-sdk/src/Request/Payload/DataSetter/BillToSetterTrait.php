<?php

namespace Elavon\Converge2\Request\Payload\DataSetter;

use Elavon\Converge2\DataObject\C2ApiFieldName;

/**
 * @method setField($field, $value)
 */
trait BillToSetterTrait
{
    public function setBillTo($value)
    {
        $this->setField(C2ApiFieldName::BILL_TO, $value);
    }
}
