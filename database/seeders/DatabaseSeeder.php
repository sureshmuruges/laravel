<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::updateOrCreate(
            ['email' => 'superadmin@example.com'],
            ['name' => 'Super Admin', 'password' => bcrypt('123456'), 'role' => 'super_admin']
        );

        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Admin', 'password' => bcrypt('123456'), 'role' => 'admin']
        );

        User::updateOrCreate(
            ['email' => 'user@example.com'],
            ['name' => 'User', 'password' => bcrypt('123456'), 'role' => 'user']
        );
    }
}

