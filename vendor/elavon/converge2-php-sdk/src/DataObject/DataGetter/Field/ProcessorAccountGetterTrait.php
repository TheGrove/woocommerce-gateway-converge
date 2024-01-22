<?php

namespace Elavon\Converge2\DataObject\DataGetter\Field;

use Elavon\Converge2\DataObject\C2ApiFieldName;

/** @method getDataField($field) */
trait ProcessorAccountGetterTrait
{
    /**
     * @return string|null
     */
    public function getProcessorAccount()
    {
        return $this->getDataField(C2ApiFieldName::PROCESSOR_ACCOUNT);
    }
}