<?php

namespace Elavon\Converge2\DataObject;

final class Source extends AbstractEnum
{
    const DIRECT_API_CALL = 'directApiCall';
    const HPP_SUBMIT_REDIRECT = 'hppSubmitRedirect';
    const HPP_IFRAME_LIGHTBOX = 'hppIframeLightbox';
    const HPP_IFRAME_EMBEDDED = 'hppIframeEmbedded';
    const HPP_SDK = 'hppSdk';
    const VIRTUAL_TERMINAL = 'virtualTerminal';
    const GATEWAY_RECURRING = 'gatewayRecurring';
    const PAY_BY_LINK = 'payByLink';
    const UNKNOWN = 'unknown';

    public function isDirectApiCall() {
        return self::DIRECT_API_CALL == $this->getValue();
    }

    public function isHppSubmitRedirect() {
        return self::HPP_SUBMIT_REDIRECT == $this->getValue();
    }

    public function isHppIframeLightBox() {
        return self::HPP_IFRAME_LIGHTBOX == $this->getValue();
    }

    public function isHppIframeEmbedded() {
        return self::HPP_IFRAME_EMBEDDED == $this->getValue();
    }

    public function isHppSdk() {
        return self::HPP_SDK == $this->getValue();
    }

    public function isVirtualTerminal() {
        return self::VIRTUAL_TERMINAL == $this->getValue();
    }

    public function isGatewayRecurring() {
        return self::GATEWAY_RECURRING == $this->getValue();
    }

    public function isPayByLink() {
        return self::PAY_BY_LINK == $this->getValue();
    }

    public function isUnknown() {
        return self::UNKNOWN == $this->getValue();
    }
}
