<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\RoleName;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Test Admin',
            'email' => 'admin@example.com',
            'password' => 'password',
            'email_verified_at' => now(),
        ])->assignRole(RoleName::ADMIN->value);

        User::create([
            'name' => 'Test Customer',
            'email' => 'customer@example.com',
            'password' => 'password',
            'email_verified_at' => now(),
        ])->assignRole(RoleName::CUSTOMER->value);
    }
}
