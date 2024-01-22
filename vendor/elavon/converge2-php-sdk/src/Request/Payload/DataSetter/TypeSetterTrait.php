<?php

namespace Elavon\Converge2\Request\Payload\DataSetter;

use Elavon\Converge2\DataObject\C2ApiFieldName;

trait TypeSetterTrait
{
    public function setType($value)
    {
        $this->setField(C2ApiFieldName::TYPE, $value);
    }
}
