<?php

namespace Database\Seeders;

use App\Models\Property;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Property::create([
            'name' => 'درجة اللزوجة',
            'category_id' => 8,
        ]);
        Property::create([
            'name' => 'حجم العبوة',
            'category_id' => 8,
        ]);
        Property::create([
            'name' => 'نوع الفلتر',
            'category_id' => 8,
        ]);

        Property::create([
            'name' => 'عرض الاطار',
            'category_id' => 10,
        ]);
        Property::create([
            'name' => 'ارتفاع الاطار',
            'category_id' => 10,
        ]);
        Property::create([
            'name' => 'قطر الجنط',
            'category_id' => 10,
        ]);
        Property::create([
            'name' => 'نوع الكاوتش',
            'category_id' => 10,
        ]);

        Property::create([
            'name' => 'نوع البطارية',
            'category_id' => 12,
        ]);
        Property::create([
            'name' => 'امبير البطارية',
            'category_id' => 12,
        ]);
    }
}
