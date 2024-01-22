<?php

namespace Elavon\Converge2\DataObject\DataGetter\Field;

use Elavon\Converge2\DataObject\C2ApiFieldName;

/** @method getDataField($field) */
trait ShopperReferenceGetterTrait
{
    /**
     * @return string|null
     */
    public function getShopperReference()
    {
        return $this->getDataField(C2ApiFieldName::SHOPPER_REFERENCE);
    }
}