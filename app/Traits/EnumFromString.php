<?php

namespace App\Traits;

trait EnumFromString
{
    public static function fromString(string $name): ?self
    {
        $name = mb_strtoupper($name);

        $matchedCase = collect(self::cases())
            ->filter(fn (self $case) => (
                $case->name === $name
            ))
            ->first();

        if (!$matchedCase) {
            return null;
        }

        return $matchedCase;
    }
}
