<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Department::create([
            'name' => 'ادارة المبيعات',
        ]);

        Department::create([
            'name' => 'الشكاوي',
        ]);

        Department::create([
            'name' => 'ادارة الطلبات',
        ]);

        Department::create([
            'name' => 'الدعم الفني',
        ]);

        Department::create([
            'name' => 'فواتير',
        ]);
    }
}
