<?php

namespace Elavon\Converge2\DataObject\Resource;

use Elavon\Converge2\DataObject\AmountAndCurrency;
use Elavon\Converge2\DataObject\Contact;

interface OrderInterface
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
    public function getModifiedAt();

    /**
     * @return string|null
     */
    public function getMerchant();

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
     * @return string|null
     */
    public function getDescription();

    /**
     * @return array|null
     */
    public function getItems();

    /**
     * @return Contact|null
     */
    public function getShipTo();

    /**
     * @return string|null
     */
    public function getShopperEmailAddress();

    /**
     * @return string|null
     */
    public function getShopperReference();

    /**
     * @return string|null
     */
    public function getOrderReference();

    /**
     * @return string|null
     */
    public function getCustomReference();

    /**
     * @return \stdClass|null
     */
    public function getCustomFields();
}