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
use Znck\Eloquent\Traits\BelongsToThrough;

class Account extends Model
{
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
}
