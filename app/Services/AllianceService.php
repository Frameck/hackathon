<?php

namespace App\Services;

use App\Contracts\CanProvideValidationRules;
use App\Traits\HasMakeConstructor;
use App\Traits\HasValidationRules;

class AllianceService implements CanProvideValidationRules
{
    use HasMakeConstructor;
    use HasValidationRules;

    protected static function indexValidationRules(): array
    {
        return static::baseIndexValidationRules();
    }

    public static function validationRules(): array
    {
        return [
            'alliance_id' => [
                'string',
                'max:255',
                'nullable',
                'exists:alliances,id',
            ],
            'family_friendly' => [
                'boolean',
                'nullable',
            ],
            'date' => [
                'date_format:Y-m-d',
                'nullable',
            ],
        ];
    }
}
