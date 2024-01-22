<?php

namespace Elavon\Converge2\Util;

/**
 * Converge2 Encryption for keys.
 */
class Encryption
{

    /** @var string Encryption salt */
    protected $encryptionKey;

    /**
     * Encryption constructor.
     */
    public function __construct($encryption_key)
    {
        if (empty($encryption_key)){
            throw new \Exception('Encryption key cannot be empty.');
        }

        $this->encryptionKey = $encryption_key;
    }


    /**
     * Encrypts a credential.
     *
     * @param string $data the credential value
     *
     * @return string
     */
    public function encryptCredential($data)
    {

        $data = trim($data);

        if (empty($data)) {
            return null;
        }

        if (function_exists('openssl_encrypt')) {
            $vector = openssl_random_pseudo_bytes($this->getEncryptionVectorLength());
            $data = openssl_encrypt($data, $this->getEncryptionMethod(), $this->encryptionKey, OPENSSL_RAW_DATA,
                $vector);

            return base64_encode($vector . $data);

        } else {
            return $data;
        }

    }

    /**
     * Gets the vector length for encrypting credentials.
     *
     * @return int
     */
    private function getEncryptionVectorLength()
    {

        return openssl_cipher_iv_length($this->getEncryptionMethod());
    }

    /**
     * Gets the method used for encrypting credentials.
     *
     * @return string
     */
    private function getEncryptionMethod()
    {

        $available_methods = openssl_get_cipher_methods();
        $preferred_method = 'AES-128-CBC';

        return
            in_array($preferred_method, $available_methods, true)
                ? $preferred_method
                : $available_methods[0];
    }

    /**
     * Decrypts a credential.
     *
     *
     * @param string $data the encrypted credential value
     *
     * @return string
     */
    public function decryptCredential($data)
    {

        if (empty($data)) {
            return null;
        }

        $data = base64_decode($data);

        if (function_exists('openssl_decrypt')) {
            $vector_length = $this->getEncryptionVectorLength();
            $vector = substr($data, 0, $vector_length);
            $data = substr($data, $vector_length);
            $data = openssl_decrypt($data, $this->getEncryptionMethod(), $this->encryptionKey,
                OPENSSL_RAW_DATA, $vector);
        }

        return trim($data);
    }

}