<?php

namespace App\Observers;

use App\Models\Agent;
use App\Models\SessionChat;
use Illuminate\Support\Facades\Http;

class AgentObserver
{
    /**
     * Handle the Agent "created" event.
     */
    public function created(Agent $agent): void
    {
        //
    }

    /**
     * Handle the Agent "updated" event.
     */
    public function updated(Agent $agent): void
    {
        if ($agent->isDirty('status') && $agent->status === 'online') {
            $waitingSession = SessionChat::where('status', 'waiting_agent')->first();

            if ($waitingSession) {
                $waitingSession->update([
                    'status' => 'in_agent',
                    'agent_id' => $agent->id,
                ]);

                Http::put("https://chatbot-4e187-default-rtdb.europe-west1.firebasedatabase.app/sessions/{$waitingSession->uuid}.json", [
                    'uuid' => $waitingSession->uuid,
                    'status' => $waitingSession->status,
                    'agent_id' => $agent->id,
                ]);

                $agent->updateQuietly(['status' => 'busy']);
            }
        }
    }

    /**
     * Handle the Agent "deleted" event.
     */
    public function deleted(Agent $agent): void
    {
        //
    }

    /**
     * Handle the Agent "restored" event.
     */
    public function restored(Agent $agent): void
    {
        //
    }

    /**
     * Handle the Agent "force deleted" event.
     */
    public function forceDeleted(Agent $agent): void
    {
        //
    }
}
