<?php

namespace Elavon\Converge2\DataObject;

final class CardFundingSource extends AbstractEnum
{
    const CHARGE = 'charge';
    const CREDIT = 'credit';
    const DEBIT = 'debit';
    const DEFERRED_DEBIT = 'deferredDebit';
    const PREPAID = 'prepaid';
    const UNKNOWN = 'unknown';

    public function isCharge() {
        return self::CHARGE == $this->getValue();
    }

    public function isDebit() {
        return self::DEBIT == $this->getValue();
    }

    public function isDeferredDebit() {
        return self::DEFERRED_DEBIT == $this->getValue();
    }

    public function isPrePaid() {
        return self::PREPAID == $this->getValue();
    }

    public function isUnknown() {
        return self::UNKNOWN == $this->getValue();
    }

    public function isCredit() {
        return self::CREDIT == $this->getValue();
    }
}
