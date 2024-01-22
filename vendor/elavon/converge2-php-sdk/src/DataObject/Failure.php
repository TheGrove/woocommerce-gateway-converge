<?php

namespace Elavon\Converge2\DataObject;

class Failure extends AbstractDataObject
{
    /**
     * @return string|null
     */
    public function getCode()
    {
        return $this->getDataField(C2ApiFieldName::CODE);
    }

    /**
     * @return string|null
     */
    public function getDescription()
    {
        return $this->getDataField(C2ApiFieldName::DESCRIPTION);
    }

    /**
     * @return string|null
     */
    public function getField()
    {
        return $this->getDataField(C2ApiFieldName::FIELD);
    }
}