<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Spatie\Activitylog\Models\Activity;
use Carbon\Carbon;

class LogoutSuccess
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
     * @param  \Illuminate\Auth\Events\Logout  $event
     * @return void
     */
    public function handle(Logout $event)
    {
        $event->subject = 'Logout';
        $event->description = 'Logout successful';

        activity($event->subject)->by($event->user)->withProperties([
            'attributes' => [
                'name' => $event->user->name,
                'email' => $event->user->email,
                'logged_out' => Carbon::now(),
            ],
        ])->log($event->description);
    }
}
