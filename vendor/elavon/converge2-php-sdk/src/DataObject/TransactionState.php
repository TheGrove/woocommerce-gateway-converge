<?php

namespace Elavon\Converge2\DataObject;

final class TransactionState extends AbstractEnum
{

    const AUTHORIZED = 'authorized';
    const CAPTURED = 'captured';
    const DECLINED = 'declined';
    const EXPIRED = 'expired';
    const HELD_FOR_REVIEW = 'heldForReview';
    const REJECTED = 'rejected';
    const SETTLED = 'settled';
    const SETTLEMENT_DELAYED = 'settlementDelayed';
    const UNKNOWN = 'unknown';
    const VOIDED = 'voided';

    public function isAuthorized()
    {
        return self::AUTHORIZED == $this->getValue();
    }

    public function isCaptured()
    {
        return self::CAPTURED == $this->getValue();
    }

    public function isDeclined()
    {
        return self::DECLINED == $this->getValue();
    }

    public function isExpired()
    {
        return self::EXPIRED == $this->getValue();
    }

    public function isRejected()
    {
        return self::REJECTED == $this->getValue();
    }

    public function isHeldForReview()
    {
        return self::HELD_FOR_REVIEW == $this->getValue();
    }

    public function isSettled()
    {
        return self::SETTLED == $this->getValue();
    }

    public function isSettlementDelayed()
    {
        return self::SETTLEMENT_DELAYED == $this->getValue();
    }

    public function isUnknown()
    {
        return self::UNKNOWN == $this->getValue();
    }

    public function isVoided()
    {
        return self::VOIDED == $this->getValue();
    }

    public function isRefundable()
    {
        return $this->isCaptured() || $this->isSettled() || $this->isSettlementDelayed();
    }

    public function isCapturable()
    {
        return $this->isAuthorized();
    }

    public function isVoidable()
    {
        return $this->isAuthorized() || $this->isCaptured();
    }
}
