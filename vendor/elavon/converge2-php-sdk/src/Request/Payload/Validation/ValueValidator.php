<?php

namespace Elavon\Converge2\Request\Payload\Validation;

class ValueValidator extends AbstractValidator
{
    /** @var string */
    protected $field;

    public function field($field)
    {
        $this->field = $field;
        return $this;
    }

    public function validate($value)
    {
        $this->violations = array();

        foreach ($this->constraintCollection->getConstraintArray() as $constraint) {
            foreach ($constraint->assert($value) as $violation) {
                if (isset($this->field)) {
                    $violation->prefixField($this->field);
                }
                $this->violations[] = $violation;
            }
        }
        return $this;
    }
}
