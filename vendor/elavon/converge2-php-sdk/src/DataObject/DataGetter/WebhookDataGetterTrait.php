<?php

namespace Elavon\Converge2\DataObject\DataGetter;

use Elavon\Converge2\DataObject\C2ApiFieldName;
use Elavon\Converge2\DataObject\Credentials;
use Elavon\Converge2\DataObject\DataGetter\Field\CreatedAtGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\DescriptionGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\HrefGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\IdGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\MerchantGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\ModifiedAtGetterTrait;

/**
 * @method getDataField($field)
 * @method castToDataObjectClass($field, $class)
 */
trait WebhookDataGetterTrait
{
    use IdGetterTrait;
    use HrefGetterTrait;
    use CreatedAtGetterTrait;
    use ModifiedAtGetterTrait;
    use MerchantGetterTrait;
    use DescriptionGetterTrait;

    protected function castObjectFields()
    {
        $this->castBasicAuthenticationCredentials();
    }

    /**
     * @return string|null
     */
    public function getUrl()
    {
        return $this->getDataField(C2ApiFieldName::URL);
    }

    /**
     * @return Credentials|null
     */
    public function getBasicAuthenticationCredentials()
    {
        return $this->getDataField(C2ApiFieldName::BASIC_AUTHENTICATION_CREDENTIALS);
    }

    /**
     * @return number|null
     */
    public function getApiVersion()
    {
        return $this->getDataField(C2ApiFieldName::API_VERSION);
    }

    /**
     * @return bool|null
     */
    public function getIsEnabled()
    {
        return $this->getDataField(C2ApiFieldName::IS_ENABLED);
    }

    protected function castBasicAuthenticationCredentials()
    {
        $this->castToDataObjectClass(C2ApiFieldName::BASIC_AUTHENTICATION_CREDENTIALS, Credentials::class);
    }
}
