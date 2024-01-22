<?php

namespace Elavon\Converge2\DataObject\DataGetter\Field;

use Elavon\Converge2\DataObject\C2ApiFieldName;

/**
 * @method getDataField($field)
 */
trait ReturnUrlGetterTrait
{
    /**
     * @return string|null
     */
    public function getReturnUrl()
    {
        return $this->getDataField(C2ApiFieldName::RETURN_URL);
    }
}
