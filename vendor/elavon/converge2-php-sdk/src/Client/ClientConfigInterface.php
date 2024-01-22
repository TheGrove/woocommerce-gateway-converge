<?php

namespace Elavon\Converge2\Client;

/**
 * Configuration Interface for a Converge2 Client.
 */
interface ClientConfigInterface
{

    /**
     * Get the Converge 2 API Version
     * @return string
     */
    public function getApiVersion();

    /**
     * Get the Converge 2 Test Base URI.
     * @return string
     */
    public function getSandboxBaseUri();

    /**
     * Get the Converge 2 Production Base URI.
     * @return string
     */
    public function getProductionBaseUri();

    /**
     * Returns Sandbox Base URI if sandbox mode is active, otherwise Production Base URI.
     * @return string
     */
    public function getBaseUri();

    /**
     * Set the Converge 2 Sandbox Base URI.
     * @param $base_uri
     */
    public function setSandboxBaseUri($base_uri);

    /**
     * Set the Converge 2 Production Base URI.
     * @param $base_uri
     */
    public function setProductionBaseUri($base_uri);

    /**
     * True if sandbox mode is active.
     * @return bool
     */
    public function isSandboxMode();

    /**
     * True if production mode is active.
     * @return bool
     */
    public function isProductionMode();

    /**
     * Make sandbox mode active.
     */
    public function setSandboxMode();

    /**
     * Make production mode active.
     */
    public function setProductionMode();

    /**
     * Set the Merchant Alias for authenticating with Converge2.
     * @param $merchant_alias
     */
    public function setMerchantAlias($merchant_alias);

    /**
     * Set the Public Key for authenticating with Converge2.
     * @param $public_key
     */
    public function setPublicKey($public_key);

    /**
     * Gets an 'auth' Guzzle array with Basic Auth based on Public Key.
     * @return array
     */
    public function getPublicKeyBasicAuthConfig();


    /**
     * Set the Secret Key for authenticating with Converge2.
     * @param $secret_key
     */
    public function setSecretKey($secret_key);

    /**
     * Gets an 'auth' Guzzle array with Basic Auth based on Secret Key.
     * @return array
     */
    public function getSecretKeyBasicAuthConfig();

    /**
     * Set the Proxy configuration.
     * @param string $proxy
     */
    public function setProxy($proxy);

    /**
     * Get the proxy configuration.
     * @return string
     */
    public function getProxy();

    /**
     * Set the timeout configuration.
     * @param float $timeout
     */
    public function setTimeout($timeout);

    /**
     * Get the timeout configuration.
     * @return float
     */
    public function getTimeout();
}