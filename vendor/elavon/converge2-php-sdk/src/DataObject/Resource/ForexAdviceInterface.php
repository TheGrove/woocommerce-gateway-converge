<?php

namespace Elavon\Converge2\DataObject\Resource;

use Elavon\Converge2\DataObject\AmountAndCurrency;
use Elavon\Converge2\DataObject\MarkupRateAnnotation;
use Elavon\Converge2\DataObject\ShopperInteraction;

interface ForexAdviceInterface
{
    /**
     * @return string|null
     */
    public function getId();

    /**
     * @return string|null
     */
    public function getHref();

    /**
     * @return string|null
     */
    public function getCreatedAt();

    /**
     * @return string|null
     */
    public function getExpiresAt();

    /**
     * @return string|null
     */
    public function getMerchant();

    /**
     * @return string|null
     */
    public function getProcessorAccount();

    /**
     * @return string|null
     */
    public function getStoredCard();

    /**
     * @return string|null
     */
    public function getCardNumber();

    /**
     * @return string|null
     */
    public function getMaskedNumber();

    /**
     * @return string|null
     */
    public function getLast4();

    /**
     * @return string|null
     */
    public function getBin();

    /**
     * @return string|null
     */
    public function getPanFingerprint();

    /**
     * @return AmountAndCurrency|null
     */
    public function getTotal();

    /**
     * @return string|null
     */
    public function getTotalAmount();

    /**
     * @return string|null
     */
    public function getTotalCurrencyCode();

    /**
     * @return AmountAndCurrency|null
     */
    public function getIssuerTotal();

    /**
     * @return string|null
     */
    public function getIssuerTotalAmount();

    /**
     * @return string|null
     */
    public function getIssuerTotalCurrencyCode();

    /**
     * @return string|null
     */
    public function getConversionRate();

    /**
     * @return string|null
     */
    public function getMarkupRate();

    /**
     * @return MarkupRateAnnotation|null
     */
    public function getMarkupRateAnnotation();

    /**
     * @return ShopperInteraction|null
     */
    public function getShopperInteraction();

    /**
     * @return string|null
     */
    public function getCustomReference();

    /**
     * @return \stdClass|null
     */
    public function getCustomFields();
}
