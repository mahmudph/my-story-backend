<?php

namespace App\Listeners;

use App\Events\UserLoginEvent;
use Illuminate\Support\Facades\Log;

class UserLoginListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserLoginEvent $event): void
    {
        Log::info("login: user atas nama" + $event->user->name + 'berhasil login');
    }
}
