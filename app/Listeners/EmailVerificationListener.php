<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use App\Jobs\EmailVerification;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class EmailVerificationListener
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
     * @param  object  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        EmailVerification::dispatch($event);
    }
}
