<?php

namespace App\Jobs;

use App\Models\SessionChat;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
// use Illuminate\Foundation\Queue\Queueable;

class MarkInactiveSessionsJob implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected int $minutes;
    protected int $sessionId;
    public function __construct(int $sessionId, int $minutes)
    {
        $this->minutes = $minutes;
        $this->sessionId = $sessionId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $session = SessionChat::find($this->sessionId);
        if (! $session) {
            return;
        }

        if ($session->isExpired($this->minutes)) {
            $session->markInactive();
        }
    }
}
