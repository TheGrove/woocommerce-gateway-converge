<?php

namespace Elavon\Converge2\DataObject;

/**
* @method getDataField($field)
*/

trait CastToDataObjectTrait {
    protected function castToDataObjectClass($field, $class)
    {
        $value = $this->getDataField($field);
        if (is_array($value)) {
            foreach ($value as $k => $v) {
                if ($this->canCast($v, $class)) {
                    $this->data->{$field}[$k] = new $class($v);
                }
            }
        }
        elseif ($this->canCast($value, $class)) {
            $this->data->$field = new $class($value);
        }
    }

    protected function canCast($value, $class) {
        return isset($value) && (!is_object($value) || get_class($value) != $class);
    }

    /**
     * Will be overwritten when needed.
     */
    protected function castObjectFields() {}
}