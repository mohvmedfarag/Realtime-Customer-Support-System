<?php

namespace Database\Seeders;

use App\Models\ChatTopic;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            'title' => 'doorstep خدمات',
            'parent_id' => null,
            'is_final' => false,
        ]);

        ChatTopic::create([
            'title' => 'زيوت وفلاتر',
            'parent_id' => 5,
            'is_final' => true,
        ]);

        ChatTopic::create([
            'title' => 'كاوتش',
            'parent_id' => 5,
            'is_final' => true,
        ]);

        ChatTopic::create([
            'title' => 'بطارية',
            'parent_id' => 5,
            'is_final' => true,
        ]);

        ChatTopic::create([
            'title' => 'خدمة كار كير',
            'parent_id' => 5,
            'is_final' => true,
        ]);

        ChatTopic::create([
            'title' => 'صيانة دورية',
            'parent_id' => 5,
            'is_final' => true,
        ]);

        ChatTopic::create([
            'title' => 'اخري',
            'parent_id' => 5,
            'is_final' => true,
        ]);

        ChatTopic::create([
            'title' => 'تقديم شكوي',
            'parent_id' => null,
            'is_final' => true,
        ]);


    }
}
