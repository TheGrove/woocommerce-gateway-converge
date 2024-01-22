<?php

namespace Elavon\Converge2\Request\Payload\Validation\Constraint;

use Elavon\Converge2\Request\Payload\Validation\Constraint\Violation\Violation;

class SafeString extends AbstractConstraint
{
    protected $errorMessageTemplate = 'Must match the following pattern: %s';
    protected $pattern;

    public function __construct($id, $pattern, $errorMessageTemplate = '')
    {
        $this->id = $id;
        $this->pattern = $pattern;
        parent::__construct($errorMessageTemplate);
    }

    public function assert($value)
    {
        $violations = array();

        if (!is_null($value) && (!is_string($value) || !preg_match($this->pattern, $value))) {
            $violations[] = new Violation(
                $this->id,
                $this->errorMessageTemplate,
                $value,
                array($this->id => $this->pattern)
            );
        }

        return $violations;
    }
}
