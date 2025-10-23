<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'name' => 'زيوت وفلاتر',
        ]);
        Category::create([
            'name' => 'كاوتش',
        ]);
        Category::create([
            'name' => 'بطارية',
        ]);
        Category::create([
            'name' => 'خدمة كار كير',
        ]);
        Category::create([
            'name' => 'صيانة دورية',
        ]);
        Category::create([
            'name' => 'خدمات اخري',
        ]);
        Category::create([
            'name' => 'فحص وكشف الزيت والفلاتر',
            'parent_id' => 1,
        ]);
        Category::create([
            'name' => 'تغيير زيت وفلاتر',
            'parent_id' => 1,
        ]);

        Category::create([
            'name' => 'فحص وكشف الكاوتش',
            'parent_id' => 2,
        ]);
        Category::create([
            'name' => 'تغيير كاوتش',
            'parent_id' => 2,
        ]);

        Category::create([
            'name' => 'فحص وكشف البطارية',
            'parent_id' => 3,
        ]);
        Category::create([
            'name' => 'تغيير البطارية',
            'parent_id' => 3,
        ]);

        Category::create([
            'name' => 'غسيل خارجي للسيارة',
            'parent_id' => 4,
        ]);
        Category::create([
            'name' => 'تنضيف داخلي للسيارة',
            'parent_id' => 4,
        ]);

        Category::create([
            'name' => 'صيانة 10.000 كيلو',
            'parent_id' => 5,
        ]);
        Category::create([
            'name' => 'صيانة 60.000 كيلو',
            'parent_id' => 5,
        ]);
    }
}
