<?php

namespace Elavon\Converge2\DataObject\Resource;

use Elavon\Converge2\DataObject\Card;
use Elavon\Converge2\DataObject\VerificationResults;

interface StoredCardInterface
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
    public function getShopper();

    /**
     * @return string|null
     */
    public function getHostedCard();

    /**
     * @return Card|null
     */
    public function getCard();

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