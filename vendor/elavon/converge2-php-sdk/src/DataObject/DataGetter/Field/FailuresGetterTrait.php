<?php

namespace Elavon\Converge2\DataObject\DataGetter\Field;

use Elavon\Converge2\DataObject\Failure;
use Elavon\Converge2\DataObject\C2ApiFieldName;

/**
 * @method getDataField($field)
 * @method castToDataObjectClass($field, $class)
 */
trait FailuresGetterTrait
{
    protected function castFailures()
    {
        if ($this->hasFailures()) {
            $this->castToDataObjectClass(C2ApiFieldName::FAILURES, Failure::class);
        }
    }

    /**
     * @return array|null
     */
    public function getFailures()
    {
        return $this->getDataField(C2ApiFieldName::FAILURES);
    }

    public function hasFailures()
    {
        return null != $this->getFailures();
    }

    public function hasFailuresOnField($name) {
        if (!$this->hasFailures()) {
            return false;
        }

        foreach ($this->getFailures() as $failure) {
            if ($failure->getField() == $name) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return Failure|null
     */
    public function getFirstFailure()
    {
        if (!$this->hasFailures()) {
            return null;
        }

        $failures = $this->getFailures();
        return $failures[0];
    }

    public function getFailuresAsJson()
    {
        return json_encode($this->getFailures());
    }
}
