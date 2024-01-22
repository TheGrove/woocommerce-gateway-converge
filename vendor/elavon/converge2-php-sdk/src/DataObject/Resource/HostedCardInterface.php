<?php

namespace Elavon\Converge2\DataObject\Resource;

use Elavon\Converge2\DataObject\Card;
use Elavon\Converge2\DataObject\ThreeDSecureV1;
use Elavon\Converge2\DataObject\VerificationResults;

interface HostedCardInterface
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
     * @return Card|null
     */
    public function getCard();

    /**
     * @return ThreeDSecureV1|null
     */
    public function getThreeDSecureV1();

    /**
     * @return bool|null
     */
    public function getDoVerify();

    /**
     * @return VerificationResults|null
     */
    public function getVerificationResults();

    /**
     * @return string|null
     */
    public function getCustomReference();

    /**
     * @return \stdClass|null
     */
    public function getCustomFields();
}