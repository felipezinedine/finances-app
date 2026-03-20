<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        // Seed the main user required by the project.
        User::firstOrCreate(
            ['email' => 'feliipeziinedine@gmail.com'],
            [
                'name' => 'Felipe Zinedine',
                'password' => '03102005',
                'email_verified_at' => now(),
            ]
        );
    }
}
