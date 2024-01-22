<?php

namespace Elavon\Converge2\DataObject\DataGetter;

use Elavon\Converge2\DataObject\C2ApiFieldName;
use Elavon\Converge2\DataObject\DataGetter\Field\HrefGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\IdGetterTrait;

/** @method getDataField($field) */
trait MerchantDataGetterTrait
{
    use IdGetterTrait;
    use HrefGetterTrait;

    /**
     * @return string|null
     */
    public function getLegalName()
    {
        return $this->getDataField(C2ApiFieldName::LEGAL_NAME);
    }
}
