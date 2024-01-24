<?php

namespace App\Listeners;

use App\Mail\AdminMail;
use App\Models\User;
use App\Notifications\AdminNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class SendNotification
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
    public function handle(object $event): void
    {
        $user = User::all();
        $userEmail = User::all()->pluck('email');

        foreach($user as $users) {
            Notification::send($users, new AdminNotification($event->user));
        }

    }   
}
