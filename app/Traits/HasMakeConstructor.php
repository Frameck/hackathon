<?php

namespace App\Traits;

trait HasMakeConstructor
{
    public static function make(): static
    {
        return app(static::class);
    }
}
