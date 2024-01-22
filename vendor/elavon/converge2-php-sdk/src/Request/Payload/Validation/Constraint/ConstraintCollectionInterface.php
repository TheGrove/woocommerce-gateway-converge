<?php

namespace Elavon\Converge2\Request\Payload\Validation\Constraint;

interface ConstraintCollectionInterface
{
    /**
     * @param ConstraintInterface $constraint
     * @param mixed $key
     * @return ConstraintCollectionInterface
     */
    public function add(ConstraintInterface $constraint, $key = null);

    /**
     * @param mixed $key
     * @return ConstraintCollectionInterface
     */
    public function remove($key = null);

    /** @return ConstraintInterface[] */
    public function getConstraintArray();
}