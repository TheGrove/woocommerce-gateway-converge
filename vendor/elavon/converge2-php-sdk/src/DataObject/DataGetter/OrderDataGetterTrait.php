<?php

namespace Elavon\Converge2\DataObject\DataGetter;

use Elavon\Converge2\DataObject\C2ApiFieldName;
use Elavon\Converge2\DataObject\DataGetter\Field\CreatedAtGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\CustomFieldsGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\CustomReferenceGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\DescriptionGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\HrefGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\IdGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\MerchantGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\ModifiedAtGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\OrderReferenceGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\ShipToGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\ShopperEmailAddressGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\ShopperReferenceGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\TotalGetterTrait;
use Elavon\Converge2\DataObject\OrderItem;

/**
 * @method getDataField($field)
 * @method castToDataObjectClass($field, $class)
 */
trait OrderDataGetterTrait
{
    use IdGetterTrait;
    use HrefGetterTrait;
    use CreatedAtGetterTrait;
    use ModifiedAtGetterTrait;
    use MerchantGetterTrait;
    use TotalGetterTrait;
    use DescriptionGetterTrait;
    use ShipToGetterTrait;
    use ShopperEmailAddressGetterTrait;
    use ShopperReferenceGetterTrait;
    use OrderReferenceGetterTrait;
    use CustomReferenceGetterTrait;
    use CustomFieldsGetterTrait;

    protected function castObjectFields()
    {
        $this->castTotal();
        $this->castItems();
        $this->castShipTo();
    }

    /**
     * @return array|null
     */
    public function getItems()
    {
        return $this->getDataField(C2ApiFieldName::ITEMS);
    }

    protected function castItems()
    {
        $this->castToDataObjectClass(C2ApiFieldName::ITEMS, OrderItem::class);
    }
}
