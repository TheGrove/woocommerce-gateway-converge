<?php

namespace Elavon\Converge2\DataObject\DataGetter\Field;

use Elavon\Converge2\DataObject\C2ApiFieldName;

/** @method getDataField($field) */
trait OrderGetterTrait
{
    /**
     * @return string|null
     */
    public function getOrder()
    {
        return $this->getDataField(C2ApiFieldName::ORDER);
    }
}