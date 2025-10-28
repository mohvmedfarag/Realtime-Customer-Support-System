<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Agent;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // User::create([
        //     'name' => 'Ahmed',
        //     'email' => 'ahmed@gmail.com',
        //     'password' => bcrypt('password'),
        // ]);

        // Agent::create([
        //     'name' => 'Agent',
        //     'email' => 'agent@estbn.com',
        //     'password' => bcrypt('password'),
        //     'status' => 'offline',
        // ]);

        // Agent::create([
        //     'name' => 'Agent 2',
        //     'email' => 'agent2@estbn.com',
        //     'password' => bcrypt('password'),
        //     'status' => 'offline',
        // ]);

        // Admin::create([
        //     'name' => 'Kareem',
        //     'email' => 'kareem@estbn.com',
        //     'password' => bcrypt('password'),
        // ]);
    }
}
