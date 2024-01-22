<?php

namespace Elavon\Converge2\DataObject\Resource;

use Elavon\Converge2\DataObject\DebtorAccount;
use Elavon\Converge2\DataObject\SubscriptionState;

interface SubscriptionInterface
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
     * @return string|null
     */
    public function getPlan();

    /**
     * @return string|null
     */
    public function getShopper();

    /**
     * @return DebtorAccount|null
     */
    public function getDebtorAccount();

    /**
     * @return bool|null
     */
    public function getDoForexConversion();

    /**
     * @return string|null
     */
    public function getStoredCard();

    /**
     * @return number|null
     */
    public function getBillCount();

    /**
     * @return string|null
     */
    public function getTimeZoneId();

    /**
     * @return string|null
     */
    public function getFirstBillAt();

    /**
     * @return string|null
     */
    public function getNextBillAt();

    /**
     * @return string|null
     */
    public function getPreviousBillAt();

    /**
     * @return string|null
     */
    public function getFinalBillAt();

    /**
     * @return string|null
     */
    public function getCancelRequestedAt();

    /**
     * @return number|null
     */
    public function getCancelAfterBillNumber();

    /**
     * @return number|null
     */
    public function getNextBillNumber();

    /**
     * @return SubscriptionState|null
     */
    public function getSubscriptionState();

    /**
     * @return number|null
     */
    public function getFailureCount();

    /**
     * @return string|null
     */
    public function getCustomReference();

    /**
     * @return \stdClass|null
     */
    public function getCustomFields();
}
