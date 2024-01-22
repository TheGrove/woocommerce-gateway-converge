<?php

namespace Elavon\Converge2\Request\Payload\DataSetter;

use Elavon\Converge2\DataObject\C2ApiFieldName;

/**
 * @method setField($field, $value)
 */
trait ShopperInteractionSetterTrait
{
    public function setShopperInteraction($value)
    {
        $this->setField(C2ApiFieldName::SHOPPER_INTERACTION, $value);
    }
}
