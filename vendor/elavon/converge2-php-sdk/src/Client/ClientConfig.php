<?php

namespace Elavon\Converge2\Client;

/**
 * Converge2 Specific Client Configuration.
 */
class ClientConfig implements ClientConfigInterface
{
    const TIMEOUT_DEFAULT = 10;

    protected $apiVersion = '1';
    protected $sandboxBaseUri = 'https://sandbox.api.eu.convergepay.com';
    protected $productionBaseUri = 'https://api.eu.convergepay.com';

    protected $sandboxMode = false;

    protected $merchantAlias = '';
    protected $publicKey = '';
    protected $secretKey = '';

    protected $proxy = '';
    protected $timeout = self::TIMEOUT_DEFAULT;

    /**
     * @inheritdoc
     */
    public function getApiVersion()
    {
        return $this->apiVersion;
    }

    /**
     * @inheritdoc
     */
    public function setSandboxBaseUri($base_uri)
    {
        $this->sandboxBaseUri = $base_uri;
    }

    /**
     * @inheritdoc
     */
    public function setProductionBaseUri($base_uri)
    {
        $this->productionBaseUri = $base_uri;
    }

    /**
     * @inheritdoc
     */
    public function getSandboxBaseUri()
    {
        return $this->sandboxBaseUri;
    }

    /**
     * @inheritdoc
     */
    public function getProductionBaseUri()
    {
        return $this->productionBaseUri;
    }

    /**
     * @inheritdoc
     */
    public function getBaseUri()
    {
        return $this->isSandboxMode() ? $this->getSandboxBaseUri() : $this->getProductionBaseUri();
    }

    /**
     * @inheritdoc
     */
    public function isSandboxMode()
    {
        return $this->sandboxMode;
    }

    /**
     * @inheritdoc
     */
    public function isProductionMode()
    {
        return !$this->isSandboxMode();
    }

    /**
     * @inheritdoc
     */
    public function setSandboxMode()
    {
        $this->sandboxMode = true;
    }

    /**
     * @inheritdoc
     */
    public function setProductionMode()
    {
        $this->sandboxMode = false;
    }

    /**
     * @inheritdoc
     */
    public function setMerchantAlias($merchant_alias)
    {
        $this->merchantAlias = $merchant_alias;
    }

    /**
     * @inheritdoc
     */
    public function setPublicKey($public_key)
    {
        $this->publicKey = $public_key;
    }

    /**
     * @inheritdoc
     */
    public function getPublicKeyBasicAuthConfig()
    {
        return array($this->merchantAlias, $this->publicKey, 'basic');
    }

    /**
     * @inheritdoc
     */
    public function setSecretKey($secret_key)
    {
        $this->secretKey = $secret_key;
    }

    /**
     * @inheritdoc
     */
    public function getSecretKeyBasicAuthConfig()
    {
        return array($this->merchantAlias, $this->secretKey, 'basic');
    }

    /**
     * @inheritdoc
     */
    public function setProxy($proxy)
    {
        $this->proxy = $proxy;
    }

    /**
     * @inheritdoc
     */
    public function getProxy()
    {
        return $this->proxy;
    }

    /**
     * @inheritdoc
     */
    public function setTimeout($timeout)
    {
        $this->timeout = (float) $timeout;
    }

    /**
     * @inheritdoc
     */
    public function getTimeout()
    {
        return $this->timeout;
    }
}