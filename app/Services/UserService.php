<?php

namespace App\Services;

use App\Contracts\CanProvideValidationRules;
use App\Models\User;
use App\Traits\HasMakeConstructor;
use App\Traits\HasValidationRules;
use Filament\Notifications\Notification;

class UserService implements CanProvideValidationRules
{
    use HasMakeConstructor;
    use HasValidationRules;

    public static function authorize(string $action, string $resource, ?User $user = null): bool
    {
        if (!$user) {
            $user = auth()->user();
        }

        if (app()->isLocal() && !auth()->user()) {
            $user = User::getTestingUser();
        }

        $action = constant(mb_strtoupper($action));

        return $user->can(
            $action . '_' . mb_strtolower($resource)
        );
    }

    protected static function indexValidationRules(): array
    {
        return static::baseIndexValidationRules();
    }

    protected static function storeValidationRules(): array
    {
        $rules = static::validationRules();

        $rules['password'] = collect($rules['password'])
            ->filter(fn (string $rule) => (
                !str($rule)->is('current_password')
            ))
            ->toArray();

        return $rules;
    }

    protected static function updateValidationRules(): array
    {
        $rules = collect(static::validationRules())
            ->map(fn (array $ruleSets) => (
                collect($ruleSets)
                    ->filter(fn (string $rule) => (
                        !str($rule)->is('required')
                    ))
                    ->push('sometimes')
                    ->unique()
                    ->toArray()
            ))
            ->toArray();

        return $rules;
    }

    public static function validationRules(): array
    {
        $rules = [
            'first_name' => [
                'string',
                'max:255',
                'required',
            ],
            'last_name' => [
                'string',
                'max:255',
                'required',
            ],
            'active' => [
                'boolean',
                'sometimes',
            ],
            'email' => [
                'string',
                'max:255',
                'required',
                'email',
                'unique:users,email',
            ],
            'password' => [
                'string',
                'max:255',
                'required',
                'current_password',
            ],
        ];

        if (auth()->user()) {
            $rules['email'][array_key_last($rules['email'])] = 'unique:users,email,except:' . auth()->id();
        }

        return $rules;
    }

    public function updateUserAttributesOnLogin(User $user): bool
    {
        return $user->update([
            'last_login' => now(),
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    public function dispatchLoginNotifications(User $user): void
    {
        Notification::make()
            ->title(__('filament-admin.notifications.login.success-notification.title'))
            ->body(__('filament-admin.notifications.login.success-notification.body'))
            ->success()
            ->duration(5000)
            ->send();
    }
}
