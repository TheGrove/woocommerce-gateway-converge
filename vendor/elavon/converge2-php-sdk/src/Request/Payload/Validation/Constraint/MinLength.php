<?php

namespace Elavon\Converge2\Request\Payload\Validation\Constraint;

use Elavon\Converge2\Request\Payload\Validation\Constraint\Violation\Violation;

class MinLength extends AbstractConstraint
{
    const ID = 'minLength';

    protected $id = self::ID;
    protected $errorMessageTemplate = 'Must be at least %d characters long';
    protected $min;

    public function __construct($min, $errorMessageTemplate = '')
    {
        $this->min = isset($min) ? (int)$min : null;
        parent::__construct($errorMessageTemplate);
    }

    public function assert($value)
    {
        $violations = array();

        if (isset($value) && !is_string($value)) {
            $length = -1;
        } else {
            $length = strlen($value);
        }

        if (isset($this->min) && $length < $this->min) {
            $violations[] = new Violation(
                $this->id,
                $this->errorMessageTemplate,
                $value,
                array($this->id => $this->min)
            );
        }

        return $violations;
    }
}