<?php

namespace App\Jobs;

use App\Models\Agent;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class AssignWaitingSessionsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $session;

    public function __construct($session)
    {
        $this->session = $session;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        // $agent = Agent::where('status', 'online')->first();

        // if ($agent) {
        //     $this->session->update([
        //         'status' => 'in_agent',
        //         'agent_id' => $agent->id,
        //     ]);

        //     $agent->update([
        //         'status' => 'busy'
        //     ]);
        // }
    }
}
