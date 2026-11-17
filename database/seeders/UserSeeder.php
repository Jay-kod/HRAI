<?php

namespace Database\Seeders;

use App\Enums\Gender;
use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'demo@example.com'],
            ['name' => 'Demo User', 'password' => Hash::make('password'), 'role' => Role::User, 'gender' => Gender::Female, 'date_of_birth' => '1990-04-15', 'contact' => '+1 555 0100', 'active' => true, 'email_verified_at' => now()],
        );

        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Demo Administrator', 'password' => Hash::make('password'), 'role' => Role::Admin, 'gender' => Gender::Male, 'date_of_birth' => '1985-09-20', 'contact' => '+1 555 0101', 'active' => true, 'email_verified_at' => now()],
        );
    }
}
