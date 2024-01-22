<?php

namespace Elavon\Converge2\Request\Payload\DataSetter;

use Elavon\Converge2\DataObject\C2ApiFieldName;

/**
 * @method setField($field, $value)
 */
trait CardSetterTrait
{
    public function setCard($value)
    {
        $this->setField(C2ApiFieldName::CARD, $value);
    }
}
