<?php

namespace Elavon\Converge2\DataObject;

final class CardBrand extends AbstractEnum
{
    const AMERICAN_EXPRESS = 'American Express';
    const UNION_PAY = 'UnionPay';
    const DINERS_CLUB = 'Diners Club';
    const DISCOVER = 'Discover';
    const JCB = 'JCB';
    const MAESTRO = 'Maestro';
    const MASTER_CARD = 'MasterCard';
    const MASTER_CARD_DEBIT = 'MasterCard Debit';
    const MASTER_CARD_CREDIT = 'MasterCard Credit';
    const VISA = 'Visa';
    const VISA_CREDIT = 'Visa Credit';
    const VISA_DEBIT = 'Visa Debit';
    const VISA_ELECTRON = 'Visa Electron';
    const UNKNOWN = 'Unknown';

    public function isAmericanExpress() {
        return self::AMERICAN_EXPRESS == $this->getValue();
    }

    public function isUnionPay() {
        return self::UNION_PAY == $this->getValue();
    }

    public function isDinersClub() {
        return self::DINERS_CLUB == $this->getValue();
    }

    public function isDiscover() {
        return self::DISCOVER == $this->getValue();
    }

    public function isJcb() {
        return self::JCB == $this->getValue();
    }

    public function isMaestro() {
        return self::MAESTRO == $this->getValue();
    }

    public function isMasterCard() {
        return self::MASTER_CARD == $this->getValue();
    }

    public function isMasterCardCredit() {
        return self::MASTER_CARD_CREDIT == $this->getValue();
    }

    public function isMasterCardDebit() {
        return self::MASTER_CARD_DEBIT == $this->getValue();
    }

    public function isVisa() {
        return self::VISA == $this->getValue();
    }

    public function isVisaCredit() {
        return self::VISA_CREDIT == $this->getValue();
    }

    public function isVisaDebit() {
        return self::VISA_DEBIT == $this->getValue();
    }

    public function isVisaElectron() {
        return self::VISA_ELECTRON == $this->getValue();
    }

    public function isUnknown() {
        return self::UNKNOWN == $this->getValue();
    }
}
