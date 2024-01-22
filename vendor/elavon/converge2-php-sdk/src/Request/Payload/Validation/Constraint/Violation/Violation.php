<?php

namespace Elavon\Converge2\Request\Payload\Validation\Constraint\Violation;

class Violation implements ViolationInterface
{
    const KEY_MESSAGE = 'message';
    const KEY_CONSTRAINT = 'constraint';
    const KEY_PARAMETERS = 'parameters';
    const KEY_VALUE = 'value';
    const KEY_FIELD = 'field';

    protected $violation = array(
        self::KEY_CONSTRAINT => '',
        self::KEY_MESSAGE => '',
        self::KEY_PARAMETERS => array(),
        self::KEY_VALUE => '',
        self::KEY_FIELD => '',
    );

    public function __construct($constraint, $message, $value, $parameters = array())
    {
        $this->violation[self::KEY_CONSTRAINT] = $constraint;
        $this->violation[self::KEY_MESSAGE] = $message;
        $this->violation[self::KEY_VALUE] = $value;
        $this->violation[self::KEY_PARAMETERS] = $parameters;
    }

    public function getAsArray()
    {
        return $this->violation;
    }

    public function getConstraintId()
    {
        return $this->violation[self::KEY_CONSTRAINT];
    }

    public function getOffendingValue()
    {
        return $this->violation[self::KEY_VALUE];
    }

    public function getConstraintParameterArray()
    {
        return $this->violation[self::KEY_PARAMETERS];
    }

    public function getFormattedMessage()
    {
        $msg = $this->violation[self::KEY_MESSAGE];
        $args = array_values($this->getConstraintParameterArray());
        $field = $this->getField();

        if (!empty($field)) {
            array_unshift($args, $field);
            $msg = '%s: ' . $msg;
        }

        return vsprintf($msg, $args);
    }

    public function getConstraintParameter($key = null)
    {
        if (isset($key)) {
            return isset($this->violation[self::KEY_PARAMETERS][$key])
                ? $this->violation[self::KEY_PARAMETERS][$key]
                : null;
        } else {
            return reset($this->violation[self::KEY_PARAMETERS]);
        }
    }

    public function getField()
    {
        return $this->violation[self::KEY_FIELD];
    }

    public function setField($name)
    {
        $this->violation[self::KEY_FIELD] = $name;
    }

    public function prefixField($prefix)
    {
        $field = $this->getField();
        if ($field) {
            $prefix .= '.' . $field;
        }
        $this->setField($prefix);
    }

    public function setMessage($msg)
    {
        $this->violation[self::KEY_MESSAGE] = $msg;
    }
}
