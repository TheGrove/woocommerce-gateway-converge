<?php

namespace Elavon\Converge2\Request\Payload;

use Elavon\Converge2\DataObject\C2ApiFieldName;
use Elavon\Converge2\Request\Payload\DataSetter\CardSetterTrait;
use Elavon\Converge2\Request\Payload\DataSetter\CreatedBySetterTrait;
use Elavon\Converge2\Request\Payload\DataSetter\CustomFieldsSetterTrait;
use Elavon\Converge2\Request\Payload\DataSetter\CustomReferenceSetterTrait;
use Elavon\Converge2\Request\Payload\DataSetter\DebtorAccountSetterTrait;
use Elavon\Converge2\Request\Payload\DataSetter\DescriptionSetterTrait;
use Elavon\Converge2\Request\Payload\DataSetter\HostedCardSetterTrait;
use Elavon\Converge2\Request\Payload\DataSetter\OrderReferenceSetterTrait;
use Elavon\Converge2\Request\Payload\DataSetter\OrderSetterTrait;
use Elavon\Converge2\Request\Payload\DataSetter\ShipToSetterTrait;
use Elavon\Converge2\Request\Payload\DataSetter\ShopperEmailAddressSetterTrait;
use Elavon\Converge2\Request\Payload\DataSetter\ShopperInteractionSetterTrait;
use Elavon\Converge2\Request\Payload\DataSetter\ShopperReferenceSetterTrait;
use Elavon\Converge2\Request\Payload\DataSetter\ShopperSetterTrait;
use Elavon\Converge2\Request\Payload\DataSetter\ShopperStatementSetterTrait;
use Elavon\Converge2\Request\Payload\DataSetter\StoredCardSetterTrait;
use Elavon\Converge2\Request\Payload\DataSetter\TotalSetterTrait;
use Elavon\Converge2\Request\Payload\DataSetter\TypeSetterTrait;

class TransactionDataBuilder extends AbstractDataBuilder
{
    use TypeSetterTrait;
    use TotalSetterTrait;
    use DescriptionSetterTrait;
    use DebtorAccountSetterTrait;
    use ShopperStatementSetterTrait;
    use CustomReferenceSetterTrait;
    use ShopperReferenceSetterTrait;
    use OrderReferenceSetterTrait;
    use ShopperInteractionSetterTrait;
    use ShopperSetterTrait;
    use ShipToSetterTrait;
    use ShopperEmailAddressSetterTrait;
    use OrderSetterTrait;
    use CardSetterTrait;
    use HostedCardSetterTrait;
    use StoredCardSetterTrait;
    use CreatedBySetterTrait;
    use CustomFieldsSetterTrait;

    public function setSource($value)
    {
        $this->setField(C2ApiFieldName::SOURCE, $value);
    }

    public function setForexAdvice($value)
    {
        $this->setField(C2ApiFieldName::FOREX_ADVICE, $value);
    }

    public function setParentTransaction($value)
    {
        $this->setField(C2ApiFieldName::PARENT_TRANSACTION, $value);
    }

    public function setShopperIpAddress($value)
    {
        $this->setField(C2ApiFieldName::SHOPPER_IP_ADDRESS, $value);
    }

    public function setShopperLanguageTag($value)
    {
        $this->setField(C2ApiFieldName::SHOPPER_LANGUAGE_TAG, $value);
    }

    public function setShopperTimeZone($value)
    {
        $this->setField(C2ApiFieldName::SHOPPER_TIME_ZONE, $value);
    }

    public function setSubscription($value)
    {
        $this->setField(C2ApiFieldName::SUBSCRIPTION, $value);
    }

    public function setRecurringType($value)
    {
        $this->setField(C2ApiFieldName::RECURRING_TYPE, $value);
    }

    public function setPreviousRecurringTransaction($value)
    {
        $this->setField(C2ApiFieldName::PREVIOUS_RECURRING_TRANSACTION, $value);
    }

    public function setPaymentLink($value)
    {
        $this->setField(C2ApiFieldName::PAYMENT_LINK, $value);
    }

    public function setPaymentSession($value)
    {
        $this->setField(C2ApiFieldName::PAYMENT_SESSION, $value);
    }

    public function setThreeDSecure($value)
    {
        $this->setField(C2ApiFieldName::THREE_D_SECURE, $value);
    }

    public function setIsHeldForReview($value)
    {
        $this->setField(C2ApiFieldName::IS_HELD_FOR_REVIEW, $value);
    }

    public function setDoCapture($value)
    {
        $this->setField(C2ApiFieldName::DO_CAPTURE, $value);
    }

    public function setDoSendReceipt($value)
    {
        $this->setField(C2ApiFieldName::DO_SEND_RECEIPT, $value);
    }
}
