<?php

namespace Database\Seeders;

use App\Models\SessionChat;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SessionUuidSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $updated = 0;

        SessionChat::whereNull('uuid')->chunk(100, function ($sessions) use (&$updated) {
            foreach ($sessions as $session) {
                $session->uuid = (string) Str::uuid();
                $session->save();
                $updated++;
            }
        });

        $this->command->info("تم تحديث {$updated} جلسة UUID بنجاح.");
    }
}
