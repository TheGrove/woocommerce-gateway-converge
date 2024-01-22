<?php

namespace Elavon\Converge2\Request\Payload;

use Elavon\Converge2\DataObject\C2ApiFieldName;

class CredentialsDataBuilder extends AbstractDataBuilder
{

    public function setUsername($value)
    {
        $this->setField(C2ApiFieldName::USERNAME, $value);
    }

    public function setPassword($value)
    {
        $this->setField(C2ApiFieldName::PASSWORD, $value);
    }
}
