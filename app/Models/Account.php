<?php

namespace App\Models;

use App\Traits\HasExportColumns;
use App\Traits\HasHelperFunctionsAndScopes;
use App\Traits\HasRelationships;
use App\Traits\HasSignature;
use App\Traits\HasSortableAttributes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Znck\Eloquent\Traits\BelongsToThrough;

class Account extends Model
{
    use HasSlug;
    use HasFactory;
    use SoftDeletes;
    use BelongsToThrough;
    use HasSignature;
    use HasExportColumns;
    use HasHelperFunctionsAndScopes;
    use HasRelationships;
    use HasSortableAttributes;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected $casts = [];

    // FUNCTIONS
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom($this->getSlugOrigin())
            ->saveSlugsTo('slug');
    }
}
