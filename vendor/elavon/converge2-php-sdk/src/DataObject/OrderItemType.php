<?php

namespace Elavon\Converge2\DataObject;

final class OrderItemType extends AbstractEnum
{
    const GOODS = 'goods';
    const SERVICE = 'service';
    const TAX = 'tax';
    const SHIPPING = 'shipping';
    const DISCOUNT = 'discount';
    const UNKNOWN = 'unknown';

    public function isGoods()
    {
        return self::GOODS == $this->getValue();
    }

    public function isService()
    {
        return self::SERVICE == $this->getValue();
    }

    public function isTax()
    {
        return self::TAX == $this->getValue();
    }

    public function isShipping()
    {
        return self::SHIPPING == $this->getValue();
    }

    public function isDiscount()
    {
        return self::DISCOUNT == $this->getValue();
    }

    public function isUnknown()
    {
        return self::UNKNOWN == $this->getValue();
    }
}