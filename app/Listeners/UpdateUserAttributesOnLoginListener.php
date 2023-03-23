<?php

namespace App\Listeners;

use App\Services\UserService;
use Illuminate\Auth\Events\Login;

class UpdateUserAttributesOnLoginListener
{
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     */
    public function handle(Login $event): void
    {
        $userService = UserService::make();
        $userService->updateUserAttributesOnLogin($event->user);
    }
}
