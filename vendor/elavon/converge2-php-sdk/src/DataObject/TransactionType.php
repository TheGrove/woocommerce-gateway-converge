<?php

namespace Elavon\Converge2\DataObject;

final class TransactionType extends AbstractEnum
{
    const SALE = 'sale';
    const VOID = 'void';
    const REFUND = 'refund';

    public function isSale()
    {
        return self::SALE == $this->getValue();
    }

    public function isRefund()
    {
        return self::REFUND == $this->getValue();
    }

    public function isVoid()
    {
        return self::VOID == $this->getValue();
    }
}