<?php

namespace Elavon\Converge2\Request\Payload\Validation;

use Elavon\Converge2\Request\Payload\AbstractDataBuilder;
use Elavon\Converge2\Request\Payload\Validation\Constraint\Violation\ViolationInterface;

class DataValidator extends AbstractValidator
{
    public function validate($value)
    {
        if ($value instanceof AbstractDataBuilder) {
            $data = $value->getDataAsArrayAssoc();
        } elseif (!is_array($value)) {
            $data = array($value);
        } else {
            $data = $value;
        }

        $this->violations = array();

        foreach ($this->constraintCollection->getConstraintArray() as $field => $constraint_array) {
            if (!is_array($constraint_array)) {
                $constraint_array = array($constraint_array);
            }
            foreach ($constraint_array as $constraint) {
                $constraint_violations = $constraint->assert(isset($data[$field]) ? $data[$field] : null);
                /** @var ViolationInterface $violation */
                foreach ($constraint_violations as $violation) {
                    $violation->prefixField($field);
                    $this->violations[] = $violation;
                }
            }
        }

        return $this;
    }

}
