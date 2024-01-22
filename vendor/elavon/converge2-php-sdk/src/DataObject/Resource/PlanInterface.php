<?php

namespace Elavon\Converge2\DataObject\Resource;

use Elavon\Converge2\DataObject\BillingInterval;
use Elavon\Converge2\DataObject\AmountAndCurrency;
use Elavon\Converge2\DataObject\ShopperStatement;

interface PlanInterface
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
    public function getDeletedAt();

    /**
     * @return string|null
     */
    public function getMerchant();

    /**
     * @return string|null
     */
    public function getName();

    /**
     * @return string|null
     */
    public function getDescription();

    /**
     * @return BillingInterval|null
     */
    public function getBillingInterval();

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
     * @return number|null
     */
    public function getBillCount();

    /**
     * @return AmountAndCurrency|null
     */
    public function getInitialTotal();

    /**
     * @return string|null
     */
    public function getInitialTotalAmount();

    /**
     * @return string|null
     */
    public function getInitialTotalCurrencyCode();

    /**
     * @return number|null
     */
    public function getInitialTotalBillCount();

    /**
     * @return ShopperStatement|null
     */
    public function getShopperStatement();

    /**
     * @return bool|null
     */
    public function getIsSubscribable();

    /**
     * @return string|null
     */
    public function getCustomReference();

    /**
     * @return \stdClass|null
     */
    public function getCustomFields();
}