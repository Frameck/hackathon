<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Traits\HasExportColumns;
use App\Traits\HasHelperFunctionsAndScopes;
use App\Traits\HasRelationships;
use App\Traits\HasSortableAttributes;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Lab404\Impersonate\Models\Impersonate;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use SoftDeletes;
    use HasRoles;
    use HasSlug;
    use HasHelperFunctionsAndScopes;
    use HasExportColumns;
    use Impersonate;
    use HasRelationships;
    use HasSortableAttributes;

    protected $fillable = [
        'first_name',
        'last_name',
        'active',
        'email',
        'password',
        'last_login',
        'ip',
        'user_agent',
        'email_verified_at',
    ];

    protected $exportColumns = [
        [
            'value' => 'roles.name',
            'label' => 'ruoli',
        ],
        'roles.guard_name',
        'name',
        'email',
        'last_login',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'active' => 'boolean',
        'last_login' => 'datetime',
        'email_verified_at' => 'datetime',
    ];

    protected function password(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => Hash::make($value),
        );
    }

    // FUNCTIONS
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom($this->getSlugOrigin())
            ->saveSlugsTo('slug');
    }

    public static function getTestingUser(string $role = 'super_admin'): self
    {
        return self::firstWhere('email', config('users.local.' . $role . '.email'));
    }

    public function canAccessFilament(): bool
    {
        $allowedRoles = [
            'super_admin',
            'admin',
        ];

        return $this->hasRole($allowedRoles);
    }

    public function canImpersonate(): bool
    {
        $roles = [
            'allowed' => [
                'super_admin',
            ],
        ];

        return $this->canAccessFilament()
            && $this->hasRole($roles['allowed']);
    }

    public function canBeImpersonated(): bool
    {
        $roles = [
            'not_allowed' => [
                'super_admin',
            ],
            'allowed' => [
                'admin',
            ],
        ];

        return $this->canAccessFilament()
            && $this->hasRole($roles['allowed'])
            && !$this->hasRole($roles['not_allowed']);
    }
}
