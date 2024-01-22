<?php

namespace Elavon\Converge2\DataObject;

final class ThreeDSecureV2 extends AbstractDataObject
{
    /**
     * @return string|null
     */
    public function getDirectoryServerTransactionId()
    {
        return $this->getDataField(C2ApiFieldName::DIRECTORY_SERVER_TRANSACTION_ID);
    }

    /**
     * @return string|null
     */
    public function getTransactionStatus()
    {
        return $this->getDataField(C2ApiFieldName::TRANSACTION_STATUS);
    }

    /**
     * @return string|null
     */
    public function getElectronicCommerceIndicator()
    {
        return $this->getDataField(C2ApiFieldName::ELECTRONIC_COMMERCE_INDICATOR);
    }

    /**
     * @return string|null
     */
    public function getAuthenticationValue()
    {
        return $this->getDataField(C2ApiFieldName::AUTHENTICATION_VALUE);
    }

    /**
     * @return string|null
     */
    public function getProtocolVersion()
    {
        return $this->getDataField(C2ApiFieldName::PROTOCOL_VERSION);
    }
}
