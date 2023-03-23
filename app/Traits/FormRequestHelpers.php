<?php

namespace App\Traits;

trait FormRequestHelpers
{
    public function getModelName(): string
    {
        return str(static::class)
            ->classBasename()
            ->ucsplit()
            ->flip()
            ->except([
                'Index',
                'Store',
                'Update',
                'Request',
            ])
            ->keys()
            ->join('');
    }

    /**
     * Returns INDEX, STORE or UPDATE
     */
    public function getRequestType(): string
    {
        return mb_strtoupper(
            str(static::class)
                ->classBasename()
                ->ucsplit()
                ->first()
        );
    }
}
