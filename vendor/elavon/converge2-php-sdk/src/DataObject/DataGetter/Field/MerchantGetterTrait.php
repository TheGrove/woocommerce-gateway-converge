<?php

namespace Elavon\Converge2\DataObject\DataGetter\Field;

use Elavon\Converge2\DataObject\C2ApiFieldName;

/** @method getDataField($field) */
trait MerchantGetterTrait
{
    /**
     * @return string|null
     */
    public function getMerchant()
    {
        return $this->getDataField(C2ApiFieldName::MERCHANT);
    }
}