<?php

namespace Database\Seeders;

use App\Models\BattaryType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BattaryTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BattaryType::create([
            'name' => 'حمض الرصاص',
        ]);
        BattaryType::create([
            'name' => 'ليثيوم أيون',
        ]);
        BattaryType::create([
            'name' => 'Agm',
        ]);
    }
}
