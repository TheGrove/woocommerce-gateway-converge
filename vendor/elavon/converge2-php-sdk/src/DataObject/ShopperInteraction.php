<?php

namespace Elavon\Converge2\DataObject;

final class ShopperInteraction extends AbstractEnum
{
    const ECOMMERCE = 'ecommerce';
    const MAIL_ORDER = 'mailOrder';
    const TELEPHONE_ORDER = 'telephoneOrder';

    public function isEcommerce() {
        return self::ECOMMERCE == $this->getValue();
    }

    public function isMailOrder() {
        return self::MAIL_ORDER == $this->getValue();
    }

    public function isTelephoneOrder() {
        return self::TELEPHONE_ORDER == $this->getValue();
    }
}
