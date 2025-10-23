<?php

namespace App\Console\Commands;

use App\Models\SessionChat;
use Kreait\Firebase\Factory;
use Illuminate\Console\Command;

class SyncSessionsToFirebase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:sessions-firebase';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync all sessions from MySQL to Firebase Realtime Database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $factory = (new Factory)->withServiceAccount(storage_path('app/firebase_credentials.json'))
        ->withDatabaseUri('https://chatbot-4e187-default-rtdb.europe-west1.firebasedatabase.app');
        $database = $factory->createDatabase();

        $sessions = SessionChat::all();

        foreach ($sessions as $session) {
            $data = [
                'id' => $session->id,
                'uuid' => $session->uuid,
                'chat_id' => $session->chat_id,
                'agent_id' => $session->agent_id,
                'status' => $session->status,
                'created_at' => $session->created_at->toDateTimeString(),
                'updated_at' => $session->updated_at->toDateTimeString(),
            ];

            $database->getReference('sessions/' . $session->uuid)->set($data);
            $this->info("✅ Session {$session->uuid} synced to Firebase");
        }

        $this->info('🎉 All sessions synced successfully!');
    }
}
