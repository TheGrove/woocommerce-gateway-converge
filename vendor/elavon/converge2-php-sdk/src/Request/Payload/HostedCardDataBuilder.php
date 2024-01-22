<?php

namespace Elavon\Converge2\Request\Payload;

use Elavon\Converge2\DataObject\C2ApiFieldName;
use Elavon\Converge2\Request\Payload\DataSetter\CardSetterTrait;
use Elavon\Converge2\Request\Payload\DataSetter\CustomReferenceSetterTrait;
use Elavon\Converge2\Request\Payload\DataSetter\CustomFieldsSetterTrait;


class HostedCardDataBuilder extends AbstractDataBuilder
{
    use CardSetterTrait;
    use CustomReferenceSetterTrait;
    use CustomFieldsSetterTrait;

    public function setThreeDSecureV1($value)
    {
        $this->setField(C2ApiFieldName::THREE_D_SECURE_V1, $value);
    }

    public function set3dsPayerAuthenticationResponse($value)
    {
        $builder = new ThreeDSecureV1DataBuilder();
        $builder->setPayerAuthenticationResponse($value);

        $this->setThreeDSecureV1($builder->getData());
    }

    public function setDoVerify($value)
    {
        $this->setField(C2ApiFieldName::DO_VERIFY, $value);
    }
}
