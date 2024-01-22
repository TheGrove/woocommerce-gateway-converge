<?php

namespace Elavon\Converge2\Request\Payload;

use Elavon\Converge2\DataObject\C2ApiFieldName;
use Elavon\Converge2\Request\Payload\DataSetter\BillToSetterTrait;
use Elavon\Converge2\Request\Payload\DataSetter\CustomFieldsSetterTrait;
use Elavon\Converge2\Request\Payload\DataSetter\CustomReferenceSetterTrait;
use Elavon\Converge2\Request\Payload\DataSetter\HostedCardSetterTrait;
use Elavon\Converge2\Request\Payload\DataSetter\OrderSetterTrait;
use Elavon\Converge2\Request\Payload\DataSetter\ShipToSetterTrait;
use Elavon\Converge2\Request\Payload\DataSetter\ShopperEmailAddressSetterTrait;
use Elavon\Converge2\Request\Payload\DataSetter\ShopperSetterTrait;
use Elavon\Converge2\Request\Payload\DataSetter\StoredCardSetterTrait;

class PaymentSessionDataBuilder extends AbstractDataBuilder
{
    use OrderSetterTrait;
    use HostedCardSetterTrait;
    use StoredCardSetterTrait;
    use ShopperSetterTrait;
    use ShopperEmailAddressSetterTrait;
    use BillToSetterTrait;
    use ShipToSetterTrait;
    use CustomReferenceSetterTrait;
    use CustomFieldsSetterTrait;

    public function setForexAdvice($value)
    {
        $this->setField(C2ApiFieldName::FOREX_ADVICE, $value);
    }

    public function setTransaction($value)
    {
        $this->setField(C2ApiFieldName::TRANSACTION, $value);
    }

    public function setDebtorAccount($value)
    {
        $this->setField(C2ApiFieldName::DEBTOR_ACCOUNT, $value);
    }

    public function setThreeDSecure($value)
    {
        $this->setField(C2ApiFieldName::THREE_D_SECURE, $value);
    }

    public function setHppType($value)
    {
        $this->setField(C2ApiFieldName::HPP_TYPE, $value);
    }

    public function setReturnUrl($value)
    {
        $this->setField(C2ApiFieldName::RETURN_URL, $value);
    }

    public function setCancelUrl($value)
    {
        $this->setField(C2ApiFieldName::CANCEL_URL, $value);
    }

    public function setOriginUrl($value)
    {
        $this->setField(C2ApiFieldName::ORIGIN_URL, $value);
    }

    public function setDefaultLanguageTag($value)
    {
        $this->setField(C2ApiFieldName::DEFAULT_LANGUAGE_TAG, $value);
    }

    public function setDoCreateTransaction($value)
    {
        $this->setField(C2ApiFieldName::DO_CREATE_TRANSACTION, $value);
    }

    public function setDoThreeDSecure($value)
    {
        $this->setField(C2ApiFieldName::DO_THREE_D_SECURE, $value);
    }
}
