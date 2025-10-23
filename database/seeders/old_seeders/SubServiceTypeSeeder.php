<?php

namespace Database\Seeders;

use App\Models\SubServiceType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubServiceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SubServiceType::create([
            'name' => 'كشف علي زيت الماتور',
            'service_type_id' => 1,
        ]);
        SubServiceType::create([
            'name' => 'فحص تسريب زيت',
            'service_type_id' => 1,
        ]);
        SubServiceType::create([
            'name' => 'فحص فلتر الزيت وتنضيفه',
            'service_type_id' => 1,
        ]);

        SubServiceType::create([
            'name' => 'ماركة الزيت',
            'service_type_id' => 2,
        ]);
        SubServiceType::create([
            'name' => 'درجة اللزوجة',
            'service_type_id' => 2,
        ]);
        SubServiceType::create([
            'name' => 'حجم العبوة',
            'service_type_id' => 2,
        ]);
        SubServiceType::create([
            'name' => 'نوع الفلتر',
            'service_type_id' => 2,
        ]);

        SubServiceType::create([
            'name' => 'كشف ثقوب',
            'service_type_id' => 3,
        ]);
        SubServiceType::create([
            'name' => 'ملئ نيتروجين',
            'service_type_id' => 3,
        ]);
        SubServiceType::create([
            'name' => '(بدون استبدال) تبديل موضع الاطارات',
            'service_type_id' => 3,
        ]);
        SubServiceType::create([
            'name' => 'كشف اتزان',
            'service_type_id' => 3,
        ]);
        SubServiceType::create([
            'name' => 'كشف استعدال جنوط',
            'service_type_id' => 3,
        ]);

        SubServiceType::create([
            'name' => 'مقاس الكاوتش',
            'service_type_id' => 4,
        ]);
        SubServiceType::create([
            'name' => 'نوع الكاوتش',
            'service_type_id' => 4,
        ]);
        SubServiceType::create([
            'name' => 'ماركة التصنيع',
            'service_type_id' => 4,
        ]);

        SubServiceType::create([
            'name' => 'كشف علي البطارية',
            'service_type_id' => 5,
        ]);SubServiceType::create([
            'name' => 'كشف وظائف الدينامو',
            'service_type_id' => 5,
        ]);SubServiceType::create([
            'name' => 'كشف دورة الكهرباء',
            'service_type_id' => 5,
        ]);

        SubServiceType::create([
            'name' => 'نوع البطارية',
            'service_type_id' => 6,
        ]);SubServiceType::create([
            'name' => 'امبير البطارية',
            'service_type_id' => 6,
        ]);SubServiceType::create([
            'name' => 'ماركة البطارية',
            'service_type_id' => 6,
        ]);
    }
}
