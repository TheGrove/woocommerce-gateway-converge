<?php

namespace Elavon\Converge2\DataObject;

final class HppType extends AbstractEnum
{
    const FULL_PAGE_REDIRECT = 'fullPageRedirect';
    const LIGHTBOX = 'lightbox';
    const PAYMENT_LINK = 'paymentLink';

    public function isFullPageRedirect()
    {
        return self::FULL_PAGE_REDIRECT == $this->getValue();
    }

    public function isLightbox()
    {
        return self::LIGHTBOX == $this->getValue();
    }

    public function isPaymentLink()
    {
        return self::PAYMENT_LINK == $this->getValue();
    }
}
