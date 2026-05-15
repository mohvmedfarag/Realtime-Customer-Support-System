<?php

namespace Database\Seeders;

use App\Models\ChatTopic;
use Illuminate\Database\Seeder;

class ChatTopicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ChatTopic::create([
            'title' => 'طلب منتج',
            'parent_id' => null,
            'is_final' => true,
        ]);

        ChatTopic::create([
            'title' => 'مشكلة في الطلب',
            'parent_id' => null,
            'is_final' => true,
            'department_id' => 3
        ]);

        ChatTopic::create([
            'title' => 'استفسار عام',
            'parent_id' => null,
            'is_final' => true,
        ]);

        ChatTopic::create([
            'title' => 'الدفع والشحن',
            'parent_id' => null,
            'is_final' => true,
        ]);

        ChatTopic::create([
            'title' => 'تقديم شكوي',
            'parent_id' => null,
            'is_final' => true,
            'department_id' => 2
        ]);
    }
}
