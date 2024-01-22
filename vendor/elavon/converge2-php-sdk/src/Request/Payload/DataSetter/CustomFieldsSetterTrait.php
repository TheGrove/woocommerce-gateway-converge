<?php

namespace Elavon\Converge2\Request\Payload\DataSetter;

use Elavon\Converge2\DataObject\C2ApiFieldName;

trait CustomFieldsSetterTrait
{
    public function setCustomFields($value)
    {
        $this->setField(C2ApiFieldName::CUSTOM_FIELDS, $value);
    }
}
