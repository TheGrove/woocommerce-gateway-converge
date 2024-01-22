<?php

namespace Elavon\Converge2\Request\Payload\Validation\Constraint;

class BasicSafeString extends SafeString
{
    const ID = 'basicSafeString';
    const PATTERN = '/^[^%<>\/\[\]{}\\\\]*$/';
    const FORBIDDEN = '%<>\{}[]';

    public function __construct($errorMessageTemplate = '')
    {
        parent::__construct(self::ID, self::PATTERN, $errorMessageTemplate);
    }
}