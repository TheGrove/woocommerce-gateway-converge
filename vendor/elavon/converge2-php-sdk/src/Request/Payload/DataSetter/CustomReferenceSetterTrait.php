<?php

namespace Elavon\Converge2\Request\Payload\DataSetter;

use Elavon\Converge2\DataObject\C2ApiFieldName;

trait CustomReferenceSetterTrait
{
    public function setCustomReference($value)
    {
        $this->setField(C2ApiFieldName::CUSTOM_REFERENCE, $value);
    }
}
