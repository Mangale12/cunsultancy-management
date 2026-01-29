<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = [
            ['name' => 'Afghanistan'],
            ['name' => 'Albania'],
            ['name' => 'Algeria'],
            ['name' => 'Andorra'],
            ['name' => 'Angola'],
            ['name' => 'Argentina'],
            ['name' => 'Armenia'],
            ['name' => 'Australia'],
            ['name' => 'Austria'],
            ['name' => 'Azerbaijan'],

            ['name' => 'Bahamas'],
            ['name' => 'Bahrain'],
            ['name' => 'Bangladesh'],
            ['name' => 'Belarus'],
            ['name' => 'Belgium'],
            ['name' => 'Belize'],
            ['name' => 'Bolivia'],
            ['name' => 'Brazil'],
            ['name' => 'Bulgaria'],

            ['name' => 'Cambodia'],
            ['name' => 'Cameroon'],
            ['name' => 'Canada'],
            ['name' => 'Chile'],
            ['name' => 'China'],
            ['name' => 'Colombia'],
            ['name' => 'Costa Rica'],
            ['name' => 'Croatia'],
            ['name' => 'Czech Republic'],

            ['name' => 'Denmark'],
            ['name' => 'Dominican Republic'],

            ['name' => 'Ecuador'],
            ['name' => 'Egypt'],
            ['name' => 'Estonia'],
            ['name' => 'Ethiopia'],

            ['name' => 'Finland'],
            ['name' => 'France'],

            ['name' => 'Georgia'],
            ['name' => 'Germany'],
            ['name' => 'Ghana'],
            ['name' => 'Greece'],

            ['name' => 'Hong Kong'],
            ['name' => 'Hungary'],

            ['name' => 'Iceland'],
            ['name' => 'India'],
            ['name' => 'Indonesia'],
            ['name' => 'Ireland'],
            ['name' => 'Israel'],
            ['name' => 'Italy'],

            ['name' => 'Japan'],
            ['name' => 'Jordan'],

            ['name' => 'Kazakhstan'],
            ['name' => 'Kenya'],
            ['name' => 'Kuwait'],

            ['name' => 'Laos'],
            ['name' => 'Latvia'],
            ['name' => 'Lithuania'],
            ['name' => 'Luxembourg'],

            ['name' => 'Malaysia'],
            ['name' => 'Maldives'],
            ['name' => 'Mexico'],
            ['name' => 'Mongolia'],

            ['name' => 'Nepal'],
            ['name' => 'Netherlands'],
            ['name' => 'New Zealand'],
            ['name' => 'Nigeria'],
            ['name' => 'Norway'],

            ['name' => 'Pakistan'],
            ['name' => 'Philippines'],
            ['name' => 'Poland'],
            ['name' => 'Portugal'],

            ['name' => 'Qatar'],

            ['name' => 'Romania'],
            ['name' => 'Russia'],

            ['name' => 'Saudi Arabia'],
            ['name' => 'Singapore'],
            ['name' => 'South Africa'],
            ['name' => 'South Korea'],
            ['name' => 'Spain'],
            ['name' => 'Sri Lanka'],
            ['name' => 'Sweden'],
            ['name' => 'Switzerland'],

            ['name' => 'Thailand'],
            ['name' => 'Turkey'],

            ['name' => 'Ukraine'],
            ['name' => 'United Arab Emirates'],
            ['name' => 'United Kingdom'],
            ['name' => 'United States'],

            ['name' => 'Vietnam'],

            ['name' => 'Zimbabwe'],
        ];

        foreach ($countries as $country) {
            Country::firstOrCreate(
                ['name' => $country['name']],
                $country
            );
        }
    }
}
