<?php

namespace Elavon\Converge2\Request\Payload;

use Elavon\Converge2\DataObject\C2ApiFieldName;
use Elavon\Converge2\Request\Payload\DataSetter\TypeSetterTrait;


class ValueTokenDataBuilder extends AbstractDataBuilder
{
    use TypeSetterTrait;

    public function setToken($value)
    {
        $this->setField(C2ApiFieldName::TOKEN, $value);
    }

    public function setProvider($value)
    {
        $this->setField(C2ApiFieldName::PROVIDER, $value);
    }
}
