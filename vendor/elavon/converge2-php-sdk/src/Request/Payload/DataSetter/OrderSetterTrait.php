<?php

namespace Elavon\Converge2\Request\Payload\DataSetter;

use Elavon\Converge2\DataObject\C2ApiFieldName;

trait OrderSetterTrait
{
    public function setOrder($value)
    {
        $this->setField(C2ApiFieldName::ORDER, $value);
    }
}
