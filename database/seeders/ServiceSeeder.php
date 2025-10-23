<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Service::create([
            'name' => 'كشف علي زيت الماتور',
            'category_id' => 7,
        ]);
        Service::create([
            'name' => 'فحص تسريب الزيت',
            'category_id' => 7,
        ]);
        Service::create([
            'name' => 'فحص فلتر الزيت وتنضيفه',
            'category_id' => 7,
        ]);

        Service::create([
            'name' => 'كشف ثقوب',
            'category_id' => 9,
        ]);
        Service::create([
            'name' => 'ملئ نيتروجين',
            'category_id' => 9,
        ]);
        Service::create([
            'name' => 'تبديل موضع الاطارات (بدون استبدال)',
            'category_id' => 9,
        ]);
        Service::create([
            'name' => 'كشف اتزان',
            'category_id' => 9,
        ]);
        Service::create([
            'name' => 'كشف استعدال جنوط',
            'category_id' => 9,
        ]);

        Service::create([
            'name' => 'كشف علي البطارية',
            'category_id' => 11,
        ]);
        Service::create([
            'name' => 'كشف وظائف الدينامو',
            'category_id' => 11,
        ]);
        Service::create([
            'name' => 'كشف دورة الكهرباء',
            'category_id' => 11,
        ]);

        Service::create([
            'name' => 'غسيل خارجي للسيارة',
            'category_id' => 13,
        ]);
        Service::create([
            'name' => 'غسيل خارجي waterless',
            'category_id' => 13,
        ]);
        Service::create([
            'name' => 'ورنيش وتلميع السيارة',
            'category_id' => 13,
        ]);
        Service::create([
            'name' => 'ورنيش وتلميع الجنوط',
            'category_id' => 13,
        ]);

        Service::create([
            'name' => 'تنضيف داخلي يدوي',
            'category_id' => 14,
        ]);
        Service::create([
            'name' => 'تنضيف داخلي بالمكنسة',
            'category_id' => 14,
        ]);
        Service::create([
            'name' => 'تنضيف بالبخار',
            'category_id' => 14,
        ]);
        Service::create([
            'name' => 'تنضيف الفرش والدواسات',
            'category_id' => 14,
        ]);
        Service::create([
            'name' => 'تعقيم السيارة',
            'category_id' => 14,
        ]);

        Service::create([
            'name' => 'زيت المحرك',
            'category_id' => 15,
        ]);
        Service::create([
            'name' => 'تغيير فلتر زيت المحرك',
            'category_id' => 15,
        ]);
        Service::create([
            'name' => 'استبدال قابس الصرف',
            'category_id' => 15,
        ]);
        Service::create([
            'name' => 'فحص المحرك',
            'category_id' => 15,
        ]);
        Service::create([
            'name' => 'فحص الفرامل الامامية والخلفية',
            'category_id' => 15,
        ]);
        Service::create([
            'name' => 'فحص سائل البطارية',
            'category_id' => 15,
        ]);

        Service::create([
            'name' => 'زيت المحرك',
            'category_id' => 16,
        ]);
        Service::create([
            'name' => 'تغيير فلتر زيت المحرك',
            'category_id' => 16,
        ]);
        Service::create([
            'name' => 'استبدال قابس الصرف',
            'category_id' => 16,
        ]);
        Service::create([
            'name' => 'فحص المحرك',
            'category_id' => 16,
        ]);
        Service::create([
            'name' => 'فحص الفرامل الامامية والخلفية',
            'category_id' => 16,
        ]);
        Service::create([
            'name' => 'فحص سائل البطارية',
            'category_id' => 16,
        ]);
        Service::create([
            'name' => 'تغيير فلتر الهواء',
            'category_id' => 16,
        ]);
        Service::create([
            'name' => 'استبدال مرشح مكيف الهواء',
            'category_id' => 16,
        ]);
        Service::create([
            'name' => 'استبدال سائل تبريد المحرك',
            'category_id' => 16,
        ]);
        Service::create([
            'name' => 'تغيير سائل الفرامل',
            'category_id' => 16,
        ]);
    }
}
