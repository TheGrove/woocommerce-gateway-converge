<?php

namespace Elavon\Converge2\DataObject\DataGetter\Field;

use Elavon\Converge2\DataObject\C2ApiFieldName;
use Elavon\Converge2\DataObject\MarkupRateAnnotation;

/**
 * @method getDataField($field)
 * @method castToDataObjectClass($field, $class)
 */
trait MarkupRateAnnotationGetterTrait
{
    /**
     * @return MarkupRateAnnotation|null
     */
    public function getMarkupRateAnnotation()
    {
        return $this->getDataField(C2ApiFieldName::MARKUP_RATE_ANNOTATION);
    }

    protected function castMarkupRateAnnotation()
    {
        $this->castToDataObjectClass(C2ApiFieldName::MARKUP_RATE_ANNOTATION, MarkupRateAnnotation::class);
    }
}
