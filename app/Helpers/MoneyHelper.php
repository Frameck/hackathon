<?php

namespace App\Helpers;

use App\Traits\HasMakeConstructor;

class MoneyHelper
{
    use HasMakeConstructor;

    // calculation types
    const ADDITION = 1;

    const PERCENTAGE = 2;

    // variation types
    const DECREASE = 3;

    const INCREASE = 4;

    // select values
    const CALCULATION_TYPES = [
        self::ADDITION => 'addizione',
        self::PERCENTAGE => 'percentuale',
    ];

    // select values
    const VARIATION_TYPES = [
        self::DECREASE => 'diminuzione',
        self::INCREASE => 'incremento',
    ];

    const ROUND_HALF_UP = PHP_ROUND_HALF_UP;

    const ROUND_HALF_DOWN = PHP_ROUND_HALF_DOWN;

    const ROUND_UP = 5;

    const ROUND_DOWN = 6;

    public static function calculateVariation(int|float $baseValue, int|float $variationValue, ?int $calculationType = null, ?int $variationType = null): int|float
    {
        if (!$calculationType) {
            $calculationType = static::PERCENTAGE;
        }

        if (!$variationType) {
            $variationType = static::INCREASE;
        }

        if ($calculationType == static::ADDITION) {
            $finalValue = match ($variationType) {
                static::DECREASE => $baseValue - $variationValue,
                static::INCREASE => $baseValue + $variationValue,
                default => $baseValue,
            };
        }

        if ($calculationType == static::PERCENTAGE) {
            $finalValue = match ($variationType) {
                static::DECREASE => ($baseValue - ($baseValue * $variationValue / 100)),
                static::INCREASE => ($baseValue + ($baseValue * $variationValue / 100)),
                default => $baseValue,
            };
        }

        return $finalValue;
    }

    public static function applyDiscount(int|float $amount, int|float $discount): int|float
    {
        return static::calculateVariation(
            $amount,
            $discount,
            variationType: static::DECREASE
        );
    }

    public static function applyRate(int|float $amount, int|float $rate): int|float
    {
        return static::calculateVariation($amount, $rate);
    }

    public static function decoupleRate(int|float $amount, int|float $rate, ?int $roundMethod = null): array
    {
        $taxable = $amount / (1 + ($rate / 100));

        $taxable = match ($roundMethod) {
            static::ROUND_HALF_UP => round($taxable, 2, static::ROUND_HALF_UP),
            static::ROUND_HALF_DOWN => round($taxable, 2, static::ROUND_HALF_DOWN),
            static::ROUND_UP => intval(ceil($taxable)),
            static::ROUND_DOWN => intval(floor($taxable)),
            default => $taxable,
        };

        return [
            'taxable' => $taxable,
            'rate' => $amount - $taxable,
        ];
    }

    public static function formatMoney(int $amount, string $currency = '€'): string
    {
        return number_format($amount, 2, ',', '.') . $currency;
    }
}
