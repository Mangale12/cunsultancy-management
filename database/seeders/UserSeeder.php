<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Country;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
            ]
        );

        $country = Country::firstOrCreate(
            ['iso2' => 'US'],
            [
                'name' => 'United States',
                'iso3' => 'USA',
                'phone_code' => '+1',
            ]
        );

        $branch = Branch::firstOrCreate(
            ['name' => 'Main Branch'],
            [
                'country_id' => $country->id,
                'code' => 'MAIN',
                'email' => 'main@example.com',
                'phone' => '1234567890',
                'address' => '123 Main St',
            ]
        );

        Employee::firstOrCreate(
            ['user_id' => $user->id],
            [
                'branch_id' => $branch->id,
                'first_name' => 'Test',
                'last_name' => 'Employee',
                'email' => 'test@example.com',
                'phone' => '1234567890',
            ]
        );

        echo "User and Employee created: {$user->email}\n";
    }
}
