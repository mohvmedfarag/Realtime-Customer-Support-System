<?php

namespace Database\Seeders;

use App\Models\ServiceType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ServiceType::create([
            'name' => 'فحص وكشف الزيوت والفلاتر',
            'service_id' => 1,
        ]);
        ServiceType::create([
            'name' => 'تغيير زيت وفلاتر',
            'service_id' => 1,
        ]);

        ServiceType::create([
            'name' => 'فحص وكشف الكاوتش',
            'service_id' => 2,
        ]);
        ServiceType::create([
            'name' => 'تغيير كاوتش',
            'service_id' => 2,
        ]);

        ServiceType::create([
            'name' => 'فحص وكشف البطارية',
            'service_id' => 3,
        ]);
        ServiceType::create([
            'name' => 'تغيير البطارية',
            'service_id' => 3,
        ]);

        ServiceType::create([
            'name' => 'غسيل خارجي للسيارة',
            'service_id' => 4,
        ]);
        ServiceType::create([
            'name' => 'تنضيف داخلي للسيارة',
            'service_id' => 4,
        ]);

        ServiceType::create([
            'name' => 'صيانة 10000 كيلو',
            'service_id' => 5,
        ]);
        ServiceType::create([
            'name' => 'صيانة 60000 كيلو',
            'service_id' => 5,
        ]);
    }
}
