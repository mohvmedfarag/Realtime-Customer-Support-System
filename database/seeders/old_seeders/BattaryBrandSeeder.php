<?php

namespace Database\Seeders;

use App\Models\BattaryBrand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BattaryBrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BattaryBrand::create([
            'name' => 'تسلا',
            'price' => 700.00,
        ]);
        BattaryBrand::create([
            'name' => 'اكسايد',
            'price' => 950.00,
        ]);
        BattaryBrand::create([
            'name' => 'اوديسي',
            'price' => 500.00,
        ]);
        BattaryBrand::create([
            'name' => 'بوش',
            'price' => 1200.00,
        ]);
        BattaryBrand::create([
            'name' => 'اكسايد',
            'price' => 800.00,
        ]);
        BattaryBrand::create([
            'name' => 'دايهارد',
            'price' => 900.00,
        ]);
        BattaryBrand::create([
            'name' => 'أوبتيما',
            'price' => 100.00,
        ]);
    }
}
