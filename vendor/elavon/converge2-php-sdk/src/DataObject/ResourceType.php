<?php

namespace Elavon\Converge2\DataObject;

final class ResourceType extends AbstractEnum
{
    const MERCHANT = 'merchant';
    const PROCESSOR_ACCOUNT = 'processorAccount';
    const ORDER = 'order';
    const PAYMENT_LINK = 'paymentLink';
    const PAYMENT_SESSION = 'paymentSession';
    const HOSTED_CARD = 'hostedCard';
    const STORED_CARD = 'storedCard';
    const FOREX_ADVICE = 'forexAdvice';
    const TRANSACTION = 'transaction';
    const BATCH = 'batch';
    const SHOPPER = 'shopper';
    const PLAN = 'plan';
    const SUBSCRIPTION = 'subscription';
    const NOTIFICATION = 'notification';
    const WEBHOOK = 'webhook';
    const SIGNER = 'signer';
    const UNKNOWN = 'unknown';

    public function isMerchant()
    {
        return self::MERCHANT == $this->getValue();
    }

    public function isProcessorAccount()
    {
        return self::PROCESSOR_ACCOUNT == $this->getValue();
    }

    public function isOrder()
    {
        return self::ORDER == $this->getValue();
    }

    public function isPaymentLink()
    {
        return self::PAYMENT_LINK == $this->getValue();
    }

    public function isPaymentSession()
    {
        return self::PAYMENT_SESSION == $this->getValue();
    }

    public function isHostedCard()
    {
        return self::HOSTED_CARD == $this->getValue();
    }

    public function isStoredCard()
    {
        return self::STORED_CARD == $this->getValue();
    }

    public function isForexAdvice()
    {
        return self::FOREX_ADVICE == $this->getValue();
    }

    public function isTransaction()
    {
        return self::TRANSACTION == $this->getValue();
    }

    public function isBatch()
    {
        return self::BATCH == $this->getValue();
    }

    public function isShopper()
    {
        return self::SHOPPER == $this->getValue();
    }

    public function isPlan()
    {
        return self::PLAN == $this->getValue();
    }

    public function isSubscription()
    {
        return self::SUBSCRIPTION == $this->getValue();
    }

    public function isNotification()
    {
        return self::NOTIFICATION == $this->getValue();
    }

    public function isWebhook()
    {
        return self::WEBHOOK == $this->getValue();
    }

    public function isSigner()
    {
        return self::SIGNER == $this->getValue();
    }

    public function isUnknown()
    {
        return self::UNKNOWN == $this->getValue();
    }

}