<?php

namespace Elavon\Converge2\DataObject;

final class EventType extends AbstractEnum
{
    const SALE_DECLINED = 'saleDeclined';
    const SALE_AUTHORIZED = 'saleAuthorized';
    const SALE_HELD_FOR_REVIEW = 'saleHeldForReview';
    const SALE_CAPTURED = 'saleCaptured';
    const SALE_SETTLED = 'saleSettled';
    const VOID_DECLINED = 'voidDeclined';
    const VOID_AUTHORIZED = 'voidAuthorized';
    const REFUND_DECLINED = 'refundDeclined';
    const REFUND_AUTHORIZED = 'refundAuthorized';
    const REFUND_CAPTURED = 'refundCaptured';
    const REFUND_SETTLED = 'refundSettled';
    const UNKNOWN = 'unknown';

    public function isSaleDeclined()
    {
        return self::SALE_DECLINED == $this->getValue();
    }

    public function isSaleAuthorized()
    {
        return self::SALE_AUTHORIZED == $this->getValue();
    }

    public function isSaleHeldForReview()
    {
        return self::SALE_HELD_FOR_REVIEW == $this->getValue();
    }

    public function isSaleCaptured()
    {
        return self::SALE_CAPTURED == $this->getValue();
    }

    public function isSaleSettled()
    {
        return self::SALE_SETTLED == $this->getValue();
    }

    public function isVoidDeclined()
    {
        return self::VOID_DECLINED == $this->getValue();
    }

    public function isVoidAuthorized()
    {
        return self::VOID_AUTHORIZED == $this->getValue();
    }

    public function isRefundDeclined()
    {
        return self::REFUND_DECLINED == $this->getValue();
    }

    public function isRefundAuthorized()
    {
        return self::REFUND_AUTHORIZED == $this->getValue();
    }

    public function isRefundCaptured()
    {
        return self::REFUND_CAPTURED == $this->getValue();
    }

    public function isRefundSettled()
    {
        return self::REFUND_SETTLED == $this->getValue();
    }

    public function isUnknown()
    {
        return self::UNKNOWN == $this->getValue();
    }

}