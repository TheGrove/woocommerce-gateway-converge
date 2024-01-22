<?php

namespace Elavon\Converge2\Request\Payload;

use Elavon\Converge2\DataObject\C2ApiFieldName;

class ThreeDSecureV2DataBuilder extends AbstractDataBuilder
{
    public function setDirectoryServerTransactionId($value)
    {
        $this->setField(C2ApiFieldName::DIRECTORY_SERVER_TRANSACTION_ID, $value);
    }

    public function setTransactionStatus($value)
    {
        $this->setField(C2ApiFieldName::TRANSACTION_STATUS, $value);
    }

    public function setElectronicCommerceIndicator($value)
    {
        $this->setField(C2ApiFieldName::ELECTRONIC_COMMERCE_INDICATOR, $value);
    }

    public function setAuthenticationValue($value)
    {
        $this->setField(C2ApiFieldName::AUTHENTICATION_VALUE, $value);
    }

    public function setProtocolVersion($value)
    {
        $this->setField(C2ApiFieldName::PROTOCOL_VERSION, $value);
    }
}
