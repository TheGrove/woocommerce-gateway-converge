<?php

namespace Elavon\Converge2\DataObject\Resource;

use Elavon\Converge2\DataObject\Contact;
use Elavon\Converge2\DataObject\DebtorAccount;
use Elavon\Converge2\DataObject\HppType;
use Elavon\Converge2\DataObject\ThreeDSecureV2;

interface PaymentSessionInterface
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
    public function getMerchant();

    /**
     * @return string|null
     */
    public function getOrder();

    /**
     * @return string|null
     */
    public function getForexAdvice();

    /**
     * @return string|null
     */
    public function getTransaction();

    /**
     * @return string|null
     */
    public function getHostedCard();

    /**
     * @return string|null
     */
    public function getStoredCard();

    /**
     * @return string|null
     */
    public function getShopper();

    /**
     * @return DebtorAccount|null
     */
    public function getDebtorAccount();

    /**
     * @return ThreeDSecureV2|null
     */
    public function getThreeDSecure();

    /**
     * @return string|null
     */
    public function getShopperEmailAddress();

    /**
     * @return Contact|null
     */
    public function getBillTo();

    /**
     * @return Contact|null
     */
    public function getShipTo();

    /**
     * @return HppType|null
     */
    public function getHppType();

    /**
     * @return string|null
     */
    public function getReturnUrl();

    /**
     * @return string|null
     */
    public function getCancelUrl();

    /**
     * @return string|null
     */
    public function getOriginUrl();

    /**
     * @return string|null
     */
    public function getDefaultLanguageTag();

    /**
     * @return string|null
     */
    public function getCustomReference();

    /**
     * @return \stdClass|null
     */
    public function getCustomFields();

    /**
     * @return bool|null
     */
    public function getDoCreateTransaction();

    /**
     * @return bool|null
     */
    public function getDoThreeDSecure();

    /**
     * @return string|null
     */
    public function getBlik();
}
