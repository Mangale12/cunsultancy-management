<?php

namespace Database\Seeders;

use App\Models\State;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $states = [
            ['country_id' => 62, 'name' => 'Koshi Province'],
            ['country_id' => 62, 'name' => 'Madhesh Province'],
            ['country_id' => 62, 'name' => 'Bagmati Province'],
            ['country_id' => 62, 'name' => 'Gandaki Province'],
            ['country_id' => 62, 'name' => 'Lumbini Province'],
            ['country_id' => 62, 'name' => 'Karnali Province'],
            ['country_id' => 62, 'name' => 'Sudurpashchim Province'],
        ];

        foreach ($states as $state) {
            State::firstOrCreate(
                [
                    'country_id' => $state['country_id'],
                    'name' => $state['name'],
                ]
            );
        }
    }
}
