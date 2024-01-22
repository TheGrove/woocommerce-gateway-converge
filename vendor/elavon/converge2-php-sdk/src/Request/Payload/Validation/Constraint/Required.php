<?php

namespace Elavon\Converge2\Request\Payload\Validation\Constraint;

use Elavon\Converge2\Request\Payload\Validation\Constraint\Violation\Violation;

class Required extends AbstractConstraint
{
    const ID = 'required';

    protected $id = self::ID;
    protected $errorMessageTemplate = 'Must not be empty';

    public function assert($value)
    {
        $violations = array();

        if (is_null($value) || $value === '') {
            $violations[] = new Violation(
                $this->id,
                $this->errorMessageTemplate,
                $value
            );
        }

        return $violations;
    }
}
