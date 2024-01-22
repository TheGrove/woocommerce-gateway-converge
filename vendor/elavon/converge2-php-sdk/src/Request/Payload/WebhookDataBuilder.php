<?php

namespace Elavon\Converge2\Request\Payload;

use Elavon\Converge2\DataObject\C2ApiFieldName;
use Elavon\Converge2\Request\Payload\DataSetter\DescriptionSetterTrait;

class WebhookDataBuilder extends AbstractDataBuilder
{
    use DescriptionSetterTrait;

    public function setUrl($value)
    {
        $this->setField(C2ApiFieldName::URL, $value);
    }

    public function setBasicAuthenticationCredentials($value)
    {
        $this->setField(C2ApiFieldName::BASIC_AUTHENTICATION_CREDENTIALS, $value);
    }

    public function setApiVersion($value)
    {
        $this->setField(C2ApiFieldName::API_VERSION, $value);
    }

    public function setIsEnabled($value)
    {
        $this->setField(C2ApiFieldName::IS_ENABLED, $value);
    }
}
