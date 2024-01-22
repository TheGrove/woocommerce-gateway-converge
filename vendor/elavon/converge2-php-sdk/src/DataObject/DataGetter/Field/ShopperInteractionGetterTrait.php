<?php

namespace Elavon\Converge2\DataObject\DataGetter\Field;

use Elavon\Converge2\DataObject\C2ApiFieldName;
use Elavon\Converge2\DataObject\ShopperInteraction;

/**
 * @method getDataField($field)
 * @method castToDataObjectClass($field, $class)
 */
trait ShopperInteractionGetterTrait
{
    /**
     * @return ShopperInteraction|null
     */
    public function getShopperInteraction()
    {
        return $this->getDataField(C2ApiFieldName::SHOPPER_INTERACTION);
    }

    protected function castShopperInteraction()
    {
        $this->castToDataObjectClass(C2ApiFieldName::SHOPPER_INTERACTION, ShopperInteraction::class);
    }
}
