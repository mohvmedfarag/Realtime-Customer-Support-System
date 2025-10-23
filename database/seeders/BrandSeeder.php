<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Brand::create([
            'name' => 'موبيل 1',
        ]);
        Brand::create([
            'name' => 'كاسترول',
        ]);
        Brand::create([
            'name' => 'شل روتيلا',
        ]);

        Brand::create([
            'name' => 'ميشلان',
        ]);
        Brand::create([
            'name' => 'بريدجستون',
        ]);
        Brand::create([
            'name' => 'جوديير',
        ]);
        Brand::create([
            'name' => 'بيريللي',
        ]);

        Brand::create([
            'name' => 'تسلا',
        ]);
        Brand::create([
            'name' => 'اكسايد',
        ]);
        Brand::create([
            'name' => 'اوديسي',
        ]);
        Brand::create([
            'name' => 'بوش',
        ]);
        Brand::create([
            'name' => 'اكسايد',
        ]);
        Brand::create([
            'name' => 'دايهارد',
        ]);
        Brand::create([
            'name' => 'اوبتيما',
        ]);
    }
}
