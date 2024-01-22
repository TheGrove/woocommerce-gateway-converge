<?php

namespace Elavon\Converge2\DataObject\DataGetter;

use Elavon\Converge2\DataObject\C2ApiFieldName;
use Elavon\Converge2\DataObject\DataGetter\Field\CreatedAtGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\HrefGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\IdGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\MerchantGetterTrait;

/**
 * @method getDataField($field)
 */
trait SignerDataGetterTrait
{
    use IdGetterTrait;
    use HrefGetterTrait;
    use CreatedAtGetterTrait;
    use MerchantGetterTrait;

    /**
     * @return string|null
     */
    public function getWebhook()
    {
        return $this->getDataField(C2ApiFieldName::WEBHOOK);
    }

    /**
     * @return number|null
     */
    public function getVersion()
    {
        return $this->getDataField(C2ApiFieldName::VERSION);
    }

    /**
     * @return string|null
     */
    public function getSecret()
    {
        return $this->getDataField(C2ApiFieldName::SECRET);
    }
}
