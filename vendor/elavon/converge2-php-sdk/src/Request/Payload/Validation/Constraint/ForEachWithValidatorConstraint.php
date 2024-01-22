<?php

namespace Elavon\Converge2\Request\Payload\Validation\Constraint;

class ForEachWithValidatorConstraint extends ValidatorConstraint
{
    public function assert($value)
    {
        if (!is_array($value)) {
            $value = array($value);
        }

        $violations = array();
        foreach ($value as $key => $item) {
            foreach ($this->validator->validate($item)->getViolations() as $v) {
                $v->prefixField($key);
                $violations[] = $v;
            }
        }

        return $violations;
    }

}