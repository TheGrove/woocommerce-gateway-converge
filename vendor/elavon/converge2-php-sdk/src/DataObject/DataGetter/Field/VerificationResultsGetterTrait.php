<?php

namespace Elavon\Converge2\DataObject\DataGetter\Field;

use Elavon\Converge2\DataObject\VerificationResults;
use Elavon\Converge2\DataObject\C2ApiFieldName;

/**
 * @method getDataField($field)
 * @method castToDataObjectClass($field, $class)
 */
trait VerificationResultsGetterTrait
{
    /**
     * @return VerificationResults|null
     */
    public function getVerificationResults()
    {
        return $this->getDataField(C2ApiFieldName::VERIFICATION_RESULTS);
    }

    protected function castVerificationResults()
    {
        $this->castToDataObjectClass(C2ApiFieldName::VERIFICATION_RESULTS, VerificationResults::class);
    }
}