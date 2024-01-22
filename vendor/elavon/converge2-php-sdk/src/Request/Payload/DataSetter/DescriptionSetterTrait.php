<?php

namespace Elavon\Converge2\Request\Payload\DataSetter;

use Elavon\Converge2\DataObject\C2ApiFieldName;

trait DescriptionSetterTrait
{
    public function setDescription($value)
    {
        $this->setField(C2ApiFieldName::DESCRIPTION, $value);
    }
}
