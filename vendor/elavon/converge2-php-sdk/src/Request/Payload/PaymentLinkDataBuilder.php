<?php

namespace Elavon\Converge2\Request\Payload;

use Elavon\Converge2\DataObject\C2ApiFieldName;
use Elavon\Converge2\Request\Payload\DataSetter\DescriptionSetterTrait;
use Elavon\Converge2\Request\Payload\DataSetter\TotalSetterTrait;
use Elavon\Converge2\Request\Payload\DataSetter\OrderReferenceSetterTrait;
use Elavon\Converge2\Request\Payload\DataSetter\ShopperEmailAddressSetterTrait;
use Elavon\Converge2\Request\Payload\DataSetter\CustomReferenceSetterTrait;
use Elavon\Converge2\Request\Payload\DataSetter\CustomFieldsSetterTrait;

class PaymentLinkDataBuilder extends AbstractDataBuilder
{
    use DescriptionSetterTrait;
    use TotalSetterTrait;
    use OrderReferenceSetterTrait;
    use ShopperEmailAddressSetterTrait;
    use CustomReferenceSetterTrait;
    use CustomFieldsSetterTrait;

    public function setExpiresAt($value)
    {
        $this->setField(C2ApiFieldName::EXPIRES_AT, $value);
    }

    public function setDoCancel($value)
    {
        $this->setField(C2ApiFieldName::DO_CANCEL, $value);
    }

    public function setReturnUrl($value)
    {
        $this->setField(C2ApiFieldName::RETURN_URL, $value);
    }

    public function setConversionLimit($value)
    {
        $this->setField(C2ApiFieldName::CONVERSION_LIMIT, $value);
    }

    public function setDebtorAccount($value)
    {
        $this->setField(C2ApiFieldName::DEBTOR_ACCOUNT, $value);
    }
}
