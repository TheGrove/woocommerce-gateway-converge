<?php

namespace Elavon\Converge2\DataObject\Resource;

use Elavon\Converge2\DataObject\Credentials;

interface WebhookInterface
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
    public function getDescription();

    /**
     * @return string|null
     */
    public function getUrl();

    /**
     * @return Credentials|null
     */
    public function getBasicAuthenticationCredentials();

    /**
     * @return number|null
     */
    public function getApiVersion();

    /**
     * @return bool|null
     */
    public function getIsEnabled();
}