<?php

namespace Elavon\Converge2\Request\Payload\DataSetter;

use Elavon\Converge2\DataObject\C2ApiFieldName;
use Elavon\Converge2\Request\Payload\ShopperStatementDataBuilder;

/**
 * @method setField($field, $value)
 */
trait ShopperStatementSetterTrait
{
    public function setShopperStatement($value)
    {
        $this->setField(C2ApiFieldName::SHOPPER_STATEMENT, $value);
    }

    public function setShopperStatementNamePhoneUrl($name, $phone, $url)
    {
        $shopper_statement_builder = new ShopperStatementDataBuilder();
        $shopper_statement_builder->setName($name);
        $shopper_statement_builder->setPhone($phone);
        $shopper_statement_builder->setUrl($url);

        $this->setShopperStatement($shopper_statement_builder->getData());
    }
}
