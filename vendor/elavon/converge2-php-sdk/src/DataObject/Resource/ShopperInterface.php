<?php

namespace Elavon\Converge2\DataObject\Resource;

use Elavon\Converge2\DataObject\Address;

interface ShopperInterface
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
    public function getDefaultStoredCard();

    /**
     * @return string|null
     */
    public function getFullName();

    /**
     * @return string|null
     */
    public function getDescription();

    /**
     * @return string|null
     */
    public function getCompany();

    /**
     * @return Address|null
     */
    public function getPrimaryAddress();

    /**
     * @return string|null
     */
    public function getPrimaryPhone();

    /**
     * @return string|null
     */
    public function getAlternatePhone();

    /**
     * @return string|null
     */
    public function getFax();

    /**
     * @return string|null
     */
    public function getEmail();

    /**
     * @return string|null
     */
    public function getCustomReference();

    /**
     * @return \stdClass|null
     */
    public function getCustomFields();
}
