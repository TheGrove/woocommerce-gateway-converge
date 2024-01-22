<?php

namespace Elavon\Converge2\Request\Payload;

use Elavon\Converge2\DataObject\C2ApiFieldName;
use Elavon\Converge2\Request\Payload\DataSetter\CustomFieldsSetterTrait;
use Elavon\Converge2\Request\Payload\DataSetter\CustomReferenceSetterTrait;
use Elavon\Converge2\Request\Payload\DataSetter\DescriptionSetterTrait;
use Elavon\Converge2\Request\Payload\DataSetter\OrderReferenceSetterTrait;
use Elavon\Converge2\Request\Payload\DataSetter\ShipToSetterTrait;
use Elavon\Converge2\Request\Payload\DataSetter\ShopperEmailAddressSetterTrait;
use Elavon\Converge2\Request\Payload\DataSetter\ShopperReferenceSetterTrait;
use Elavon\Converge2\Request\Payload\DataSetter\TotalSetterTrait;

class OrderDataBuilder extends AbstractDataBuilder
{
    use TotalSetterTrait;
    use DescriptionSetterTrait;
    use ShipToSetterTrait;
    use ShopperEmailAddressSetterTrait;
    use ShopperReferenceSetterTrait;
    use CustomReferenceSetterTrait;
    use CustomFieldsSetterTrait;
    use OrderReferenceSetterTrait;

    public function setItems($value)
    {
        $this->setField(C2ApiFieldName::ITEMS, $value);
    }
}
