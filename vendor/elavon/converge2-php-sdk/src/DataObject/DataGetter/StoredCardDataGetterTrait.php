<?php

namespace Elavon\Converge2\DataObject\DataGetter;

use Elavon\Converge2\DataObject\DataGetter\Field\CardGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\CreatedAtGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\CustomFieldsGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\CustomReferenceGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\DeletedAtGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\HostedCardGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\HrefGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\IdGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\MerchantGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\ModifiedAtGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\ShopperGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\VerificationResultsGetterTrait;

/**
 * @method getDataField($field)
 */
trait StoredCardDataGetterTrait
{
    use IdGetterTrait;
    use HrefGetterTrait;
    use CreatedAtGetterTrait;
    use ModifiedAtGetterTrait;
    use DeletedAtGetterTrait;
    use MerchantGetterTrait;
    use ShopperGetterTrait;
    use HostedCardGetterTrait;
    use CardGetterTrait;
    use VerificationResultsGetterTrait;
    use CustomReferenceGetterTrait;
    use CustomFieldsGetterTrait;

    protected function castObjectFields()
    {
        $this->castCard();
        $this->castVerificationResults();
    }
}
