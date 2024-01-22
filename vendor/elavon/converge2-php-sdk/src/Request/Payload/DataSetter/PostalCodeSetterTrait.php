<?php

namespace Elavon\Converge2\Request\Payload\DataSetter;

use Elavon\Converge2\DataObject\C2ApiFieldName;

/**
 * @method setField($field, $value)
 */
trait PostalCodeSetterTrait
{
    public function setPostalCode($value)
    {
        $this->setField(C2ApiFieldName::POSTAL_CODE, $value);
    }
}
