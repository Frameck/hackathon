<?php

namespace App\Http\Livewire;

use App\Models\User;
use Filament\Http\Livewire\Auth\Login as FilamentLogin;
use Filament\Notifications\Notification;

class Login extends FilamentLogin
{
    public function mount(): void
    {
        parent::mount();

        if (!app()->isLocal()) {
            return;
        }

        $testingUser = User::getTestingUser();
        if (!$testingUser) {
            Notification::make()
                ->title(__('filament-admin.notifications.login.warning-notification.title'))
                ->body(__('filament-admin.notifications.login.warning-notification.body'))
                ->warning()
                ->duration(10000)
                ->send();
        } else {
            $this->form->fill([
                'email' => $testingUser->email,
                'password' => config('users.local.super_admin.password'),
            ]);
        }
    }
}
