<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasTreeStructure
{
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class);
    }

    public function childrens(bool $recursive = false): HasMany
    {
        $hasMany = $this->hasMany(self::class, 'parent_id');

        if (!$recursive) {
            return $hasMany;
        }

        return $hasMany->with('childrens');
    }
}
