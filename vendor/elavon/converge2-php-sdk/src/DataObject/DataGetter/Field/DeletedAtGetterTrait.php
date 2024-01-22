<?php

namespace Elavon\Converge2\DataObject\DataGetter\Field;

use Elavon\Converge2\DataObject\C2ApiFieldName;

/**
 * @method getDataField($field)
 */
trait DeletedAtGetterTrait
{
    /**
     * @return string|null
     */
    public function getDeletedAt()
    {
        return $this->getDataField(C2ApiFieldName::DELETED_AT);
    }
}