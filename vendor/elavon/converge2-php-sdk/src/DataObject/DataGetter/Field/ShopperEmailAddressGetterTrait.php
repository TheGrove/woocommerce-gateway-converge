<?php

namespace Elavon\Converge2\DataObject\DataGetter\Field;

use Elavon\Converge2\DataObject\C2ApiFieldName;

/** @method getDataField($field) */
trait ShopperEmailAddressGetterTrait
{
    /**
     * @return string|null
     */
    public function getShopperEmailAddress()
    {
        return $this->getDataField(C2ApiFieldName::SHOPPER_EMAIL_ADDRESS);
    }
}