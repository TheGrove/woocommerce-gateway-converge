<?php

namespace Elavon\Converge2\DataObject\DataGetter\Field;

use Elavon\Converge2\DataObject\C2ApiFieldName;

/** @method getDataField($field) */
trait CustomFieldsGetterTrait
{
    /**
     * @return \stdClass|null
     */
    public function getCustomFields()
    {
        return $this->getDataField(C2ApiFieldName::CUSTOM_FIELDS);
    }
}