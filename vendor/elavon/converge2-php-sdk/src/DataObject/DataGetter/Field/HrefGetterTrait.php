<?php

namespace Elavon\Converge2\DataObject\DataGetter\Field;

use Elavon\Converge2\DataObject\C2ApiFieldName;

/** @method getDataField($field) */
trait HrefGetterTrait
{
    /**
     * @return string|null
     */
    public function getHref()
    {
        return $this->getDataField(C2ApiFieldName::HREF);
    }
}