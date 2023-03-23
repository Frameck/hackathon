<?php

namespace App\Helpers;

use ErrorException;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Stringable;

class AppHelper
{
    public static function getModelsNames(): array
    {
        $files = scandir(
            app_path('Models')
        );

        $models = [];
        foreach ($files as $fileName) {
            $fileName = str($fileName);

            if ($fileName->is([
                '.',
                '..',
            ])) {
                continue;
            }

            $models[] = $fileName->before('.php')->toString();
        }

        return $models;
    }

    public static function getSnakeCaseModelsNames(): array
    {
        return Arr::map(
            static::getModelsNames(),
            fn (string $model) => (
                str($model)
                    ->snake()
                    ->toString()
            )
        );
    }

    public static function tranformJsonToPhp(string $json): string
    {
        return str($json)
            ->replace(
                ['{', '}', '"', ': '],
                ['[', ']', '\'', ' => '],
            )
            ->toString();
    }

    public static function transformExcelDate($value, $format = 'd/m/Y')
    {
        $date = $value;
        if (!($value instanceof \Carbon\Carbon)) {
            // if not a istanceof Carbon, try to make it
            try {
                $date = \Carbon\Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value));
            } catch (ErrorException $e) {
                $date = \Carbon\Carbon::createFromFormat($format, $value);
            }
        }

        return $date;
    }

    public static function normalizeLinks(string|array $links): string|array
    {
        return collect($links)
            ->map(fn (string $link) => (
                str($link)
                    ->whenStartsWith(
                        ['http://', 'https://'],
                        fn (Stringable $link) => $link,
                        fn (Stringable $link) => $link->prepend('https://'),
                    )
                    ->toString()
            ))
            ->when(
                is_string($links),
                fn (Collection $links) => $links->first(),
                fn (Collection $links) => $links->toArray(),
            );
    }
}
