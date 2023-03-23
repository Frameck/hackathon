<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Blade;

class BladeHelper
{
    public static function registerDirectives(): void
    {
        Blade::directive('money', function (string|int $values) {
            $values = explode(',', $values);

            $currency = '€';
            if (isset($values[1])) {
                $currency = trim($values[1]);
            }

            return MoneyHelper::formatMoney($values[0], $currency);
        });

        Blade::directive('normalizeLink', fn (string $link) => (
            AppHelper::normalizeLinks($link)
        ));
    }
}
