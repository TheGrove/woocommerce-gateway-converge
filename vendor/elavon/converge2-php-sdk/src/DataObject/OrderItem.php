<?php

namespace Elavon\Converge2\DataObject;

use Elavon\Converge2\DataObject\DataGetter\Field\CustomReferenceGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\DescriptionGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\TotalGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\UnitPriceGetterTrait;

final class OrderItem extends AbstractDataObject
{
    use TotalGetterTrait;
    use DescriptionGetterTrait;
    use UnitPriceGetterTrait;
    use CustomReferenceGetterTrait;

    protected function castObjectFields()
    {
        $this->castTotal();
        $this->castUnitPrice();
        $this->castType();
    }

    /**
     * @return string|null
     */
    public function getType()
    {
        return $this->getDataField(C2ApiFieldName::TYPE);
    }

    /**
     * @return number|null
     */
    public function getQuantity()
    {
        return $this->getDataField(C2ApiFieldName::QUANTITY);
    }

    protected function castType()
    {
        $this->castToDataObjectClass(C2ApiFieldName::TYPE, OrderItemType::class);
    }
}