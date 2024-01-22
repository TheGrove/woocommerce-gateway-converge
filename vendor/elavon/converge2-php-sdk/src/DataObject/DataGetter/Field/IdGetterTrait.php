<?php

namespace Elavon\Converge2\DataObject\DataGetter\Field;

use Elavon\Converge2\DataObject\C2ApiFieldName;

/** @method getDataField($field) */
trait IdGetterTrait
{
    /**
     * @return string|null
     */
    public function getId()
    {
        return $this->getDataField(C2ApiFieldName::ID);
    }
}