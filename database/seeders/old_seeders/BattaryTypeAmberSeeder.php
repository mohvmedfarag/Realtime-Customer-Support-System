<?php

namespace Database\Seeders;

use App\Models\BattaryTypeAmber;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BattaryTypeAmberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BattaryTypeAmber::create([
           'battary_type_id' => 1,
           'battary_amber_id' => 5,
        ]);

        BattaryTypeAmber::create([
           'battary_type_id' => 3,
           'battary_amber_id' => 1,
        ]);
        BattaryTypeAmber::create([
           'battary_type_id' => 3,
           'battary_amber_id' => 2,
        ]);
        BattaryTypeAmber::create([
           'battary_type_id' => 3,
           'battary_amber_id' => 3,
        ]);

        BattaryTypeAmber::create([
           'battary_type_id' => 2,
           'battary_amber_id' => 4,
        ]);
        BattaryTypeAmber::create([
           'battary_type_id' => 2,
           'battary_amber_id' => 1,
        ]);
    }
}
