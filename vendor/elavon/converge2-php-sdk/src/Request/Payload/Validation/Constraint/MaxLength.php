<?php

namespace Elavon\Converge2\Request\Payload\Validation\Constraint;

use Elavon\Converge2\Request\Payload\Validation\Constraint\Violation\Violation;

class MaxLength extends AbstractConstraint
{
    const ID = 'maxLength';

    protected $id = self::ID;
    protected $errorMessageTemplate = 'Must be at most %d characters long';
    protected $max;

    public function __construct($max, $errorMessageTemplate = '')
    {
        $this->max = isset($max) ? (int)$max : null;
        parent::__construct($errorMessageTemplate);
    }

    public function assert($value)
    {
        $violations = array();
        if (isset($value) && !is_string($value)) {
            $length = INF;
        } else {
            if ($value === null || $value === ''){
                    $length = 0;
            }
            else {
                $length = strlen($value);
            }
        }

        if (isset($this->max) && $length > $this->max) {
            $violations[] = new Violation(
                $this->id,
                $this->errorMessageTemplate,
                $value,
                array($this->id => $this->max)
            );
        }

        return $violations;
    }
}
