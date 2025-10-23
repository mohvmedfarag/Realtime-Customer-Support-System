<?php

namespace App\Jobs;

use App\Models\Agent;
use Kreait\Firebase\Factory;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Http;

class AssignSessionToAgentWebJob implements ShouldQueue
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
        $agent = Agent::where('status', 'online')->first();

        if ($agent) {
            $this->session->agent_id = $agent->id;
            $this->session->status = 'in_agent';
            $this->session->last_agent_activity = null;
            $this->session->save();

            $agent->status = 'busy';
            $agent->save();

            Http::put("https://chatbot-4e187-default-rtdb.europe-west1.firebasedatabase.app/sessions/{$this->session->uuid}.json", [
                'uuid' => $this->session->uuid,
                'status' => $this->session->status,
                'agent_id' => $this->session->agent_id,
                'agent_name' => $this->session->agent->name,
                'user_id' => $this->session->chat->user_id,
            ]);

            // CheckAgentInactivityJob::dispatch($this->session)->delay(now()->addSeconds(30));
        }
    }
}
