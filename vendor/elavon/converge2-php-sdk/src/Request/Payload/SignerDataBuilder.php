<?php

namespace Elavon\Converge2\Request\Payload;

use Elavon\Converge2\DataObject\C2ApiFieldName;

class SignerDataBuilder extends AbstractDataBuilder
{

    public function setWebhook($value)
    {
        $this->setField(C2ApiFieldName::WEBHOOK, $value);
    }

    public function setVersion($value)
    {
        $this->setField(C2ApiFieldName::VERSION, $value);
    }
}
