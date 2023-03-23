<?php

namespace App\Contracts;

interface CanProvideValidationRules
{
    public static function validationRules(): array;
}
