<?php

namespace Elavon\Converge2\DataObject\DataGetter\Field;

use Elavon\Converge2\DataObject\C2ApiFieldName;
use Elavon\Converge2\DataObject\ShopperStatement;

/**
 * @method getDataField($field)
 * @method castToDataObjectClass($field, $class)
 */
trait ShopperStatementGetterTrait
{
    /**
     * @return ShopperStatement|null
     */
    public function getShopperStatement()
    {
        return $this->getDataField(C2ApiFieldName::SHOPPER_STATEMENT);
    }

    protected function castShopperStatement()
    {
        $this->castToDataObjectClass(C2ApiFieldName::SHOPPER_STATEMENT, ShopperStatement::class);
    }
}