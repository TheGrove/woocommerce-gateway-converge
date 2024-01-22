<?php

namespace Elavon\Converge2\DataObject;

final class Credentials extends AbstractDataObject
{

    /**
     * @return string|null
     */
    public function getUsername()
    {
        return $this->getDataField(C2ApiFieldName::USERNAME);
    }

    /**
     * @return string|null
     */
    public function getPassword()
    {
        return $this->getDataField(C2ApiFieldName::PASSWORD);
    }

}