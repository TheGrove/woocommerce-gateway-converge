<?php

namespace Elavon\Converge2\DataObject\DataGetter\Field;

use Elavon\Converge2\DataObject\C2ApiFieldName;

/** @method getDataField($field) */
trait ShopperGetterTrait
{
    /**
     * @return string|null
     */
    public function getShopper()
    {
        return $this->getDataField(C2ApiFieldName::SHOPPER);
    }
}