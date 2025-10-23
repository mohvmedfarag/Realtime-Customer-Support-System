<?php

namespace App\Listeners;

use App\Events\SessionWaitingAgent;
use App\Jobs\AssignWaitingSessionsJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AssignSessionToAgent
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
    public function handle(SessionWaitingAgent $event): void
    {
        // AssignWaitingSessionsJob::dispatch();
    }
}
