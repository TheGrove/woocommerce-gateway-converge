<?php

namespace Elavon\Converge2\DataObject\DataGetter\Field;

use Elavon\Converge2\DataObject\Card;
use Elavon\Converge2\DataObject\C2ApiFieldName;

/**
 * @method getDataField($field)
 * @method castToDataObjectClass($field, $class)
 */
trait CardGetterTrait
{
    /**
     * @return Card|null
     */
    public function getCard()
    {
        return $this->getDataField(C2ApiFieldName::CARD);
    }

    public function castCard()
    {
        $this->castToDataObjectClass(C2ApiFieldName::CARD, Card::class);
    }
}