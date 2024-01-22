<?php

namespace Elavon\Converge2\Request\Payload\DataSetter;

use Elavon\Converge2\DataObject\C2ApiFieldName;

/**
 * @method setField($field, $value)
 */
trait OrderReferenceSetterTrait
{
    public function setOrderReference($value)
    {
        $this->setField(C2ApiFieldName::ORDER_REFERENCE, $value);
    }
}
