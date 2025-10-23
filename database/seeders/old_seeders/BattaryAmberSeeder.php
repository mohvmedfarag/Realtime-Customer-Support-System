<?php

namespace Database\Seeders;

use App\Models\BattaryAmber;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BattaryAmberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BattaryAmber::create([
            'amber' => '750.00 أمبير',
        ]);
        BattaryAmber::create([
            'amber' => '1000.00 أمبير',
        ]);
        BattaryAmber::create([
            'amber' => '600.00 أمبير',
        ]);
        BattaryAmber::create([
            'amber' => '12000.00 أمبير',
        ]);
        BattaryAmber::create([
            'amber' => '450.00 أمبير',
        ]);
    }
}
