<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BattaryAmberBrand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BattaryAmberBrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BattaryAmberBrand::create([
            'battary_amber_id' => 1,
            'battary_brand_id' => 3,
        ]);
        BattaryAmberBrand::create([
            'battary_amber_id' => 1,
            'battary_brand_id' => 7,
        ]);

        BattaryAmberBrand::create([
            'battary_amber_id' => 2,
            'battary_brand_id' => 4,
        ]);

        BattaryAmberBrand::create([
            'battary_amber_id' => 3,
            'battary_brand_id' => 5,
        ]);

        BattaryAmberBrand::create([
            'battary_amber_id' => 4,
            'battary_brand_id' => 6,
        ]);

        BattaryAmberBrand::create([
            'battary_amber_id' => 5,
            'battary_brand_id' => 1,
        ]);
        BattaryAmberBrand::create([
            'battary_amber_id' => 5,
            'battary_brand_id' => 2,
        ]);
    }
}
