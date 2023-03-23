<?php

namespace App\Listeners;

use App\Services\UserService;
use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;

class DispatchLoginNotificationsListener implements ShouldQueue
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
        $userService->dispatchLoginNotifications($event->user);
    }
}
