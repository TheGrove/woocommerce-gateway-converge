<?php

namespace Elavon\Converge2\DataObject;

use Elavon\Converge2\DataObject\DataGetter\Field\ProcessorAccountGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\TotalGetterTrait;

final class ThreeDSecureV1 extends AbstractDataObject
{
    use ProcessorAccountGetterTrait;
    use TotalGetterTrait;

    protected function castObjectFields()
    {
        $this->castTotal();
        $this->castIsSupported();
        $this->castIsSuccessful();
    }

    public function isSupported()
    {
        $supported = $this->getIsSupported();
        return isset($supported) && $supported->isTrue();
    }

    public function isSuccessful()
    {
        $successful = $this->getIsSuccessful();
        return isset($successful) && $successful->isTrue();
    }

    /**
     * @return string|null
     */
    public function getUserAgent()
    {
        return $this->getDataField(C2ApiFieldName::USER_AGENT);
    }

    /**
     * @return string|null
     */
    public function getAcceptHeader()
    {
        return $this->getDataField(C2ApiFieldName::ACCEPT_HEADER);
    }

    /**
     * @return TrueFalseOrUnknown|null
     */
    public function getIsSupported()
    {
        return $this->getDataField(C2ApiFieldName::IS_SUPPORTED);
    }

    /**
     * @return string|null
     */
    public function getAccessControlServerUrl()
    {
        return $this->getDataField(C2ApiFieldName::ACCESS_CONTROL_SERVER_URL);
    }

    /**
     * @return string|null
     */
    public function getPayerAuthenticationRequest()
    {
        return $this->getDataField(C2ApiFieldName::PAYER_AUTHENTICATION_REQUEST);
    }

    /**
     * @return string|null
     */
    public function getPayerAuthenticationResponse()
    {
        return $this->getDataField(C2ApiFieldName::PAYER_AUTHENTICATION_RESPONSE);
    }

    /**
     * @return TrueFalseOrUnknown|null
     */
    public function getIsSuccessful()
    {
        return $this->getDataField(C2ApiFieldName::IS_SUCCESSFUL);
    }

    protected function castIsSupported()
    {
        $this->castToDataObjectClass(C2ApiFieldName::IS_SUPPORTED, TrueFalseOrUnknown::class);
    }

    protected function castIsSuccessful()
    {
        $this->castToDataObjectClass(C2ApiFieldName::IS_SUCCESSFUL, TrueFalseOrUnknown::class);
    }
}
