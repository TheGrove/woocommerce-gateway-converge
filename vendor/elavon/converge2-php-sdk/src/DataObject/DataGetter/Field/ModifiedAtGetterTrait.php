<?php

namespace Elavon\Converge2\DataObject\DataGetter\Field;

use Elavon\Converge2\DataObject\C2ApiFieldName;

/** @method getDataField($field) */
trait ModifiedAtGetterTrait
{
    /**
     * @return string|null
     */
    public function getModifiedAt()
    {
        return $this->getDataField(C2ApiFieldName::MODIFIED_AT);
    }
}