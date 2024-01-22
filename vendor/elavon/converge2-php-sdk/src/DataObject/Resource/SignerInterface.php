<?php

namespace Elavon\Converge2\DataObject\Resource;

interface SignerInterface
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
    public function getMerchant();

    /**
     * @return string|null
     */
    public function getWebhook();

    /**
     * @return number|null
     */
    public function getVersion();

    /**
     * @return string|null
     */
    public function getSecret();
}