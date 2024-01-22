<?php

namespace Elavon\Converge2\Request\Payload\DataSetter;

use Elavon\Converge2\DataObject\C2ApiFieldName;

/**
 * @method setField($field, $value)
 */
trait StoredCardSetterTrait
{
    public function setStoredCard($value)
    {
        $this->setField(C2ApiFieldName::STORED_CARD, $value);
    }
}
