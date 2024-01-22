<?php

namespace Elavon\Converge2\Request\Payload;

use Elavon\Converge2\DataObject\C2ApiFieldName;
use Elavon\Converge2\Request\Payload\DataSetter\DebtorAccountSetterTrait;
use Elavon\Converge2\Request\Payload\DataSetter\StoredCardSetterTrait;
use Elavon\Converge2\Request\Payload\DataSetter\BillCountSetterTrait;
use Elavon\Converge2\Request\Payload\DataSetter\CustomReferenceSetterTrait;
use Elavon\Converge2\Request\Payload\DataSetter\CustomFieldsSetterTrait;

class SubscriptionDataBuilder extends AbstractDataBuilder
{
    use DebtorAccountSetterTrait;
    use StoredCardSetterTrait;
    use BillCountSetterTrait;
    use CustomReferenceSetterTrait;
    use CustomFieldsSetterTrait;

    public function setPlan($value)
    {
        $this->setField(C2ApiFieldName::PLAN, $value);
    }

    public function setDoForexConversion($value)
    {
        $this->setField(C2ApiFieldName::DO_FOREX_CONVERSION, $value);
    }

    public function setTimeZoneId($value)
    {
        $this->setField(C2ApiFieldName::TIME_ZONE_ID, $value);
    }

    public function setFirstBillAt($value)
    {
        $this->setField(C2ApiFieldName::FIRST_BILL_AT, $value);
    }

    public function setCancelAfterBillNumber($value)
    {
        $this->setField(C2ApiFieldName::CANCEL_AFTER_BILL_NUMBER, $value);
    }
}
