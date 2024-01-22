<?php

namespace Elavon\Converge2\DataObject\DataGetter;

use Elavon\Converge2\DataObject\C2ApiFieldName;
use Elavon\Converge2\DataObject\DataGetter\Field\BinGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\ConversionRateGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\CreatedAtGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\CustomFieldsGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\CustomReferenceGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\ExpiresAtGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\HrefGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\IdGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\IssuerTotalGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\Last4GetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\MarkupRateAnnotationGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\MarkupRateGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\MaskedNumberGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\MerchantGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\PanFingerprintGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\ProcessorAccountGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\ShopperInteractionGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\StoredCardGetterTrait;
use Elavon\Converge2\DataObject\DataGetter\Field\TotalGetterTrait;

/**
 * @method getDataField($field)
 */
trait ForexAdviceDataGetterTrait
{
    use IdGetterTrait;
    use HrefGetterTrait;
    use CreatedAtGetterTrait;
    use ExpiresAtGetterTrait;
    use MerchantGetterTrait;
    use ProcessorAccountGetterTrait;
    use StoredCardGetterTrait;
    use MaskedNumberGetterTrait;
    use Last4GetterTrait;
    use BinGetterTrait;
    use PanFingerprintGetterTrait;
    use TotalGetterTrait;
    use IssuerTotalGetterTrait;
    use ConversionRateGetterTrait;
    use MarkupRateGetterTrait;
    use MarkupRateAnnotationGetterTrait;
    use ShopperInteractionGetterTrait;
    use CustomReferenceGetterTrait;
    use CustomFieldsGetterTrait;

    protected function castObjectFields()
    {
        $this->castTotal();
        $this->castIssuerTotal();
        $this->castMarkupRateAnnotation();
        $this->castShopperInteraction();
    }

    /**
     * @return string|null
     */
    public function getCardNumber()
    {
        return $this->getDataField(C2ApiFieldName::CARD_NUMBER);
    }
}
