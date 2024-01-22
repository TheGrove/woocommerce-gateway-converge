<?php

namespace Elavon\Converge2\DataObject;

final class CardScheme extends AbstractEnum
{
    const AMERICAN_EXPRESS = 'American Express';
    const UNION_PAY = 'UnionPay';
    const DISCOVER = 'Discover';
    const JCB = 'JCB';
    const MASTER_CARD = 'MasterCard';
    const VISA = 'Visa';
    const UNKNOWN = 'Unknown';

    public function isAmericanExpress() {
        return self::AMERICAN_EXPRESS == $this->getValue();
    }

    public function isUnionPay() {
        return self::UNION_PAY == $this->getValue();
    }

    public function isDiscover() {
        return self::DISCOVER == $this->getValue();
    }

    public function isJcb() {
        return self::JCB == $this->getValue();
    }

    public function isMasterCard() {
        return self::MASTER_CARD == $this->getValue();
    }

    public function isVisa() {
        return self::VISA == $this->getValue();
    }

    public function isUnknown() {
        return self::UNKNOWN == $this->getValue();
    }
}
