<?php

namespace Elavon\Converge2\DataObject\DataGetter\Field;

use Elavon\Converge2\DataObject\C2ApiFieldName;

/** @method getDataField($field) */
trait ExpiresAtGetterTrait
{
    /**
     * @return string|null
     */
    public function getExpiresAt()
    {
        return $this->getDataField(C2ApiFieldName::EXPIRES_AT);
    }
}