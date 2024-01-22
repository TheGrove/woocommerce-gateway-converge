<?php

namespace Elavon\Converge2\DataObject\DataGetter\Field;

use Elavon\Converge2\DataObject\C2ApiFieldName;

/** @method getDataField($field) */
trait StoredCardGetterTrait
{
    /**
     * @return string|null
     */
    public function getStoredCard()
    {
        return $this->getDataField(C2ApiFieldName::STORED_CARD);
    }
}
