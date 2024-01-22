<?php

namespace Elavon\Converge2\Request\Payload;

use Elavon\Converge2\DataObject\C2ApiFieldName;

class ShopperStatementDataBuilder extends AbstractDataBuilder
{

    public function setName($value)
    {
        $this->setField(C2ApiFieldName::NAME, $value);
    }

    public function setPhone($value)
    {
        $this->setField(C2ApiFieldName::PHONE, $value);
    }

    public function setUrl($value)
    {
        $this->setField(C2ApiFieldName::URL, $value);
    }
}