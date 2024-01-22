<?php

namespace Elavon\Converge2\Request\Payload\DataSetter;

use Elavon\Converge2\DataObject\C2ApiFieldName;

trait ShopperEmailAddressSetterTrait
{
    public function setShopperEmailAddress($value)
    {
        $this->setField(C2ApiFieldName::SHOPPER_EMAIL_ADDRESS, $value);
    }
}
