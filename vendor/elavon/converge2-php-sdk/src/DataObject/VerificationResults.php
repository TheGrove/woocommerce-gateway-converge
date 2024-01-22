<?php

namespace Elavon\Converge2\DataObject;

final class VerificationResults extends AbstractDataObject
{
    protected function castObjectFields()
    {
        $this->castName();
        $this->castSecurityCode();
        $this->castAddressStreet();
        $this->castAddressPostalCode();
        $this->castThreeDSecureV1();
        $this->castThreeDSecureV2();
    }

    /**
     * @return Verification|null
     */
    public function getName()
    {
        return $this->getDataField(C2ApiFieldName::NAME);
    }

    /**
     * @return Verification|null
     */
    public function getSecurityCode()
    {
        return $this->getDataField(C2ApiFieldName::SECURITY_CODE);
    }

    /**
     * @return Verification|null
     */
    public function getAddressStreet()
    {
        return $this->getDataField(C2ApiFieldName::ADDRESS_STREET);
    }

    /**
     * @return Verification|null
     */
    public function getAddressPostalCode()
    {
        return $this->getDataField(C2ApiFieldName::ADDRESS_POSTAL_CODE);
    }

    /**
     * @return Verification|null
     */
    public function getThreeDSecureV1()
    {
        return $this->getDataField(C2ApiFieldName::THREE_D_SECURE_V1);
    }

    /**
     * @return Verification|null
     */
    public function getThreeDSecureV2()
    {
        return $this->getDataField(C2ApiFieldName::THREE_D_SECURE_V2);
    }

    protected function castName()
    {
        $this->castToDataObjectClass(C2ApiFieldName::NAME, Verification::class);
    }

    protected function castSecurityCode()
    {
        $this->castToDataObjectClass(C2ApiFieldName::SECURITY_CODE, Verification::class);
    }

    protected function castAddressStreet()
    {
        $this->castToDataObjectClass(C2ApiFieldName::ADDRESS_STREET, Verification::class);
    }

    protected function castAddressPostalCode()
    {
        $this->castToDataObjectClass(C2ApiFieldName::ADDRESS_POSTAL_CODE, Verification::class);
    }

    protected function castThreeDSecureV1()
    {
        $this->castToDataObjectClass(C2ApiFieldName::THREE_D_SECURE_V1, Verification::class);
    }

    protected function castThreeDSecureV2()
    {
        $this->castToDataObjectClass(C2ApiFieldName::THREE_D_SECURE_V2, Verification::class);
    }
}
