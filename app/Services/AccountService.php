<?php

namespace App\Services;

use App\Contracts\CanProvideValidationRules;
use App\Traits\HasMakeConstructor;
use App\Traits\HasValidationRules;

class AccountService implements CanProvideValidationRules
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
            'account_id' => [
                'string',
                'max:255',
                'nullable',
                'exists:accounts,id',
            ],
            'alliance_id' => [
                'string',
                'max:255',
                'nullable',
                'exists:alliances,id',
            ],
            'session_count' => [
                'integer',
                'numeric',
                'nullable',
            ],
            'session_duration' => [
                'decimal:0',
                'nullable',
            ],
            'transaction_count' => [
                'integer',
                'numeric',
                'nullable',
            ],
            'revenue' => [
                'decimal:0',
                'nullable',
            ],
            'date' => [
                'date_format:Y-m-d',
                'nullable',
            ],
            'active' => [
                'boolean',
                'nullable',
            ],
            'account_state' => [
                'integer',
                'numeric',
                'nullable',
            ],
            'last_active_date' => [
                'date_format:Y-m-d',
                'nullable',
            ],
            'level' => [
                'decimal:0',
                'nullable',
            ],
            'created_language' => [
                'string',
                'max:255',
                'nullable',
            ],
            'created_country_code' => [
                'string',
                'max:255',
                'nullable',
            ],
            'created_time' => [
                'date_format:Y-m-d H:i:s',
                'nullable',
            ],
            'session_count_today' => [
                'integer',
                'numeric',
                'nullable',
            ],
            'session_duration_today' => [
                'decimal:0',
                'nullable',
            ],
            'transaction_count_today' => [
                'integer',
                'numeric',
                'nullable',
            ],
            'revenue_today' => [
                'decimal:0',
                'nullable',
            ],
            'last_login_game_client_language' => [
                'string',
                'max:255',
                'nullable',
            ],
            'last_login_country_code' => [
                'string',
                'max:255',
                'nullable',
            ],
        ];
    }
}
