<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\SessionChat;
use App\Models\Agent;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CheckAgentInactivityJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $session;

    /**
     * Create a new job instance.
     */
    public function __construct($session)
    {
        $this->session = $session;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $session = SessionChat::findOrFail($this->session->id);

        $lastMessage = $session->messages()->latest()->first();

        if ($lastMessage && $lastMessage->sender === 'agent') {
            Log::info("⛔ تم إيقاف CheckAgentInactivityJob لأن آخر رسالة من الـ agent في السيشن {$session->uuid}");
            return;
        }


        $isInactive = !$session->last_agent_activity || $session->last_agent_activity < now()->subSeconds(30);

        if ($session && $session->status === 'in_agent' && $isInactive) {

            if ($session->agent) {
                $session->agent->update(['status' => 'online']);
            }

            $newAgent = Agent::where('status', 'online')
            ->where('id', '!=', $session->agent_id)
            ->first();

            if ($newAgent) {
                $session->update([
                    'agent_id' => $newAgent->id,
                    'last_agent_activity' => now(),
                ]);

                $newAgent->update(['status' => 'busy']);

                // Update Firebase
                Http::put("https://chatbot-4e187-default-rtdb.europe-west1.firebasedatabase.app/sessions/{$session->uuid}.json", [
                    'uuid' => $session->uuid,
                    'status' => $session->status,
                    'agent_id' => $newAgent->id,
                    'agent_name' => $newAgent->name,
                    'user_id' => $session->chat->user_id,
                ]);

                Log::info("Session {$session->uuid} reassigned to Agent {$newAgent->id}");
            }else{
                $session->update([
                    'status' => 'waiting_agent',
                    'agent_id' => null,
                ]);

                // Update Firebase
                Http::put("https://chatbot-4e187-default-rtdb.europe-west1.firebasedatabase.app/sessions/{$session->uuid}.json", [
                    'uuid' => $session->uuid,
                    'status' => $session->status,
                    'agent_id' => null,
                    'user_id' => $session->chat->user_id,
                ]);
            }
        }
    }
}
