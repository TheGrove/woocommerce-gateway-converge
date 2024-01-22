<?php

namespace Elavon\Converge2\Schema;

/**
 * Class Schema - load and query Converge schema rules.
 */
final class Converge2Schema
{
    const COMMON_MAX_LENGTH = 255;
    const SHOPPER_STATEMENT_NAME_MAX_LENGTH = 25;
    const SHOPPER_STATEMENT_PHONE_MAX_LENGTH = 20;
    const SHOPPER_STATEMENT_URL_MAX_LENGTH = 13;
    const CUSTOM_FIELD_VALUE_MAX_LENGTH = 1024;
    const ORDER_MAX_ITEMS = 256;

    private static $instance;

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __clone()
    {
    }

    public function __wakeup()
    {
    }

    public function getShopperReferenceMaxLength()
    {
        return $this->getCommonMaxLength();
    }

    public function getShopperStatementNameMaxLength()
    {
        return self::SHOPPER_STATEMENT_NAME_MAX_LENGTH;
    }

    public function getShopperStatementPhoneMaxLength()
    {
        return self::SHOPPER_STATEMENT_PHONE_MAX_LENGTH;
    }

    public function getShopperStatementUrlMaxLength()
    {
        return self::SHOPPER_STATEMENT_URL_MAX_LENGTH;
    }

    public function getCustomFieldsValueMaxLength()
    {
        return self::CUSTOM_FIELD_VALUE_MAX_LENGTH;
    }

    public function getOrderMaxItems()
    {
        return self::ORDER_MAX_ITEMS;
    }

    public function getContactFullNameMaxLength()
    {
        return $this->getCommonMaxLength();
    }

    public function getCommonMaxLength()
    {
        return self::COMMON_MAX_LENGTH;
    }

}
