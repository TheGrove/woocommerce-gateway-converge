<?php

namespace Elavon\Converge2\Request\Payload\DataSetter;

use Elavon\Converge2\DataObject\C2ApiFieldName;

/**
 * @method setField($field, $value)
 */
trait CreatedBySetterTrait
{
    public function setCreatedBy($value)
    {
        $this->setField(C2ApiFieldName::CREATED_BY, $value);
    }
}
