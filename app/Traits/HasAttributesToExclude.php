<?php

namespace App\Traits;

trait HasAttributesToExclude
{
    protected function getAttributesToExclude(): array
    {
        return array_merge(
            $this->getDefaultAttributesToExclude(),
            $this->getAdditionalAttributesToExclude()
        );
    }

    protected function getDefaultAttributesToExclude(): array
    {
        return [
            'created_by',
            'updated_by',
            'created_at',
            'updated_at',
            'deleted_at',
        ];
    }
}
