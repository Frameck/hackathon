<?php

namespace App\Traits;

trait HasModelLabelAndRecordTitleAttribute
{
    public static function getRecordTitleAttribute(): ?string
    {
        $slugOrigin = app(self::getModel())->getSlugOrigin();
        if (is_array($slugOrigin)) {
            return $slugOrigin[0];
        }

        return $slugOrigin;
    }

    public static function getModelLabel(): string
    {
        return str(self::getModel())
            ->classBasename()
            ->ucsplit()
            ->map(fn ($value) => mb_strtolower($value))
            ->join(' ');
    }

    public static function getPluralModelLabel(): string
    {
        return str(str(self::getModel())
            ->classBasename()
            ->ucsplit()
            ->map(fn ($value) => mb_strtolower($value))
            ->join(' '))
            ->plural();
    }
}
