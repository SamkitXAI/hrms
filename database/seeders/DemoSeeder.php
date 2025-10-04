<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::firstOrCreate(
            ['code' => 'DEMO'],
            ['name' => 'Demo Company', 'employee_cap' => 500]
        );

        User::firstOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'role' => 'superadmin',
                'is_active' => true
            ]
        );

        User::firstOrCreate(
            ['email' => 'hr@example.com'],
            [
                'name' => 'HR Admin',
                'password' => Hash::make('password'),
                'company_id' => $company->id,
                'role' => 'hr',
                'is_active' => true
            ]
        );
    }
}
