<?php

namespace App\Traits;

use Illuminate\Contracts\Validation\Rule as RuleContract;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

trait HasValidationRules
{
    public static function getValidationRules(?string $type = null): array
    {
        $rules = match ($type) {
            'index', INDEX => method_exists(static::class, 'indexValidationRules') ? static::indexValidationRules() : static::baseIndexValidationRules(),
            'store', STORE => static::storeValidationRules(),
            'update', UPDATE => static::updateValidationRules(),
            default => static::storeValidationRules(),
        };

        return Arr::map($rules, fn (array $ruleSets) => (
            Arr::flatten($ruleSets)
        ));
    }

    protected static function baseIndexValidationRules(): array
    {
        $modelName = str(static::class)
            ->classBasename()
            ->before('Service')
            ->prepend('App\Models\\')
            ->toString();

        return [
            'all' => [
                'boolean',
            ],
            'per_page' => [
                'integer',
            ],
            'ids' => [
                'array',
            ],
            'ids.*' => [
                Rule::exists($modelName::getTableName(), 'id'),
            ],
            'sort_by' => [
                Rule::in($modelName::getSortableColumns()),
            ],
            'sort_direction' => [
                Rule::in([
                    'asc',
                    'desc',
                ]),
            ],
        ];
    }

    protected static function storeValidationRules(): array
    {
        return static::validationRules();
    }

    /**
     * On update the required is removed from validation rules because it's not needed
     */
    protected static function updateValidationRules(): array
    {
        return Arr::map(
            static::validationRules(),
            fn (array $ruleSets) => (
                collect($ruleSets)
                    ->filter(function (RuleContract|string $rule) {
                        if ($rule instanceof RuleContract) {
                            return true;
                        }

                        return !str($rule)->is('required');
                    })
                    ->toArray()
            )
        );
    }
}
