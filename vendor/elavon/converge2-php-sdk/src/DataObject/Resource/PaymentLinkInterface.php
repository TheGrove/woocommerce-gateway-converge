<?php

namespace Elavon\Converge2\DataObject\Resource;

use Elavon\Converge2\DataObject\AmountAndCurrency;
use Elavon\Converge2\DataObject\DebtorAccount;

interface PaymentLinkInterface
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
    public function getExpiresAt();

    /**
     * @return string|null
     */
    public function getCancelledAt();

    /**
     * @return bool|null
     */
    public function getDoCancel();

    /**
     * @return string|null
     */
    public function getMerchant();

    /**
     * @return string|null
     */
    public function getUrl();

    /**
     * @return string|null
     */
    public function getReturnUrl();

    /**
     * @return string|null
     */
    public function getDescription();

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
    public function getConversionLimit();

    /**
     * @return number|null
     */
    public function getConversionCount();

    /**
     * @return number|null
     */
    public function getClickCount();

    /**
     * @return DebtorAccount|null
     */
    public function getDebtorAccount();

    /**
     * @return string|null
     */
    public function getOrderReference();

    /**
     * @return string|null
     */
    public function getShopperEmailAddress();

    /**
     * @return string|null
     */
    public function getCustomReference();

    /**
     * @return \stdClass|null
     */
    public function getCustomFields();
}
