<?php

namespace Elavon\Converge2\DataObject\DataGetter\Field;

use Elavon\Converge2\DataObject\C2ApiFieldName;

/** @method getDataField($field) */
trait CreatedByGetterTrait
{
    /**
     * @return string|null
     */
    public function getCreatedBy()
    {
        return $this->getDataField(C2ApiFieldName::CREATED_BY);
    }
}