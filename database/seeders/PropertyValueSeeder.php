<?php

namespace Database\Seeders;

use App\Models\PropertyValue;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PropertyValueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // PropertyValue::create([
        //     'value' => '5w-40',
        //     'property_id' => 1,
        // ]);
        // PropertyValue::create([
        //     'value' => '5w-30',
        //     'property_id' => 1,
        // ]);
        // PropertyValue::create([
        //     'value' => '0w-30',
        //     'property_id' => 1,
        // ]);
        // PropertyValue::create([
        //     'value' => '5w-40',
        //     'property_id' => 1,
        // ]);

        PropertyValue::create([
            'value' => '20',
            'property_id' => 2,
        ]);
        PropertyValue::create([
            'value' => '40',
            'property_id' => 2,
        ]);
        PropertyValue::create([
            'value' => '5',
            'property_id' => 2,
        ]);
        PropertyValue::create([
            'value' => '30',
            'property_id' => 2,
        ]);

        PropertyValue::create([
            'value' => 'Bosch Premium Oil Filter',
            'property_id' => 3,
        ]);
        PropertyValue::create([
            'value' => 'K&N Performance Oil Filter',
            'property_id' => 3,
        ]);
        PropertyValue::create([
            'value' => 'Mann-Filter Oil Filter',
            'property_id' => 3,
        ]);
        PropertyValue::create([
            'value' => 'Fram Extra Guard Oil Filter',
            'property_id' => 3,
        ]);

        PropertyValue::create([
            'value' => '215',
            'property_id' => 4,
        ]);
        PropertyValue::create([
            'value' => '225',
            'property_id' => 4,
        ]);
        PropertyValue::create([
            'value' => '235',
            'property_id' => 4,
        ]);
        PropertyValue::create([
            'value' => '245',
            'property_id' => 4,
        ]);
        PropertyValue::create([
            'value' => '205',
            'property_id' => 4,
        ]);

        PropertyValue::create([
            'value' => '60',
            'property_id' => 5,
        ]);
        PropertyValue::create([
            'value' => '65',
            'property_id' => 5,
        ]);
        PropertyValue::create([
            'value' => '70',
            'property_id' => 5,
        ]);
        PropertyValue::create([
            'value' => '75',
            'property_id' => 5,
        ]);
        PropertyValue::create([
            'value' => '55',
            'property_id' => 5,
        ]);

        PropertyValue::create([
            'value' => '16',
            'property_id' => 6,
        ]);
        PropertyValue::create([
            'value' => '17',
            'property_id' => 6,
        ]);
        PropertyValue::create([
            'value' => '18',
            'property_id' => 6,
        ]);
        PropertyValue::create([
            'value' => '19',
            'property_id' => 6,
        ]);
        PropertyValue::create([
            'value' => '20',
            'property_id' => 6,
        ]);

        PropertyValue::create([
            'value' => 'شعاعي',
            'property_id' => 7,
        ]);
        PropertyValue::create([
            'value' => 'انحراف',
            'property_id' => 7,
        ]);
        PropertyValue::create([
            'value' => 'بدون انبوب',
            'property_id' => 7,
        ]);
        PropertyValue::create([
            'value' => 'مع انبوب',
            'property_id' => 7,
        ]);

        PropertyValue::create([
            'value' => 'حمض الرصاص',
            'property_id' => 8,
        ]);
        PropertyValue::create([
            'value' => 'Agm',
            'property_id' => 8,
        ]);
        PropertyValue::create([
            'value' => 'ليثيوم ايون',
            'property_id' => 8,
        ]);

        PropertyValue::create([
            'value' => '450 امبير',
            'property_id' => 9,
        ]);
        PropertyValue::create([
            'value' => '750 امبير',
            'property_id' => 9,
        ]);
        PropertyValue::create([
            'value' => '1000 امبير',
            'property_id' => 9,
        ]);
        PropertyValue::create([
            'value' => '600 امبير',
            'property_id' => 9,
        ]);
        PropertyValue::create([
            'value' => '1200 امبير',
            'property_id' => 9,
        ]);

    }
}
