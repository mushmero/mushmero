<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Spatie\Activitylog\Models\Activity;
use Carbon\Carbon;

class LoginSuccess
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \Illuminate\Auth\Events\Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        $event->subject = 'Login';
        $event->description = 'Login successful';

        activity($event->subject)->by($event->user)->withProperties([
            'attributes' => [
                'name' => $event->user->name,
                'email' => $event->user->email,
                'logged_in' => Carbon::now(),
            ],
        ])->log($event->description);
    }
}
