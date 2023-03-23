<?php

namespace App\Traits;

trait HasSortableAttributes
{
    public static function getSortableColumns()
    {
        if (property_exists(static::class, 'sortable')) {
            return (new static)->sortable;
        }

        return static::getTableColumns();
    }
}
