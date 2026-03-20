<?php

namespace Database\Seeders;

use App\Models\Categories;
use App\Models\User;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'feliipeziinedine@gmail.com')->first();
        if (! $user) {
            return;
        }

        $user->categories()->firstOrCreate(
            ['name' => 'Alimentação', 'type' => 'despesa'],
            ['icon' => '🍔', 'color' => '#f5b942']
        );

        $user->categories()->firstOrCreate(
            ['name' => 'Transporte', 'type' => 'despesa'],
            ['icon' => '🚗', 'color' => '#f04060']
        );

        $user->categories()->firstOrCreate(
            ['name' => 'Moradia', 'type' => 'despesa'],
            ['icon' => '🏠', 'color' => '#7c5cfc']
        );

        $user->categories()->firstOrCreate(
            ['name' => 'Saúde', 'type' => 'despesa'],
            ['icon' => '💊', 'color' => '#f06292']
        );

        $user->categories()->firstOrCreate(
            ['name' => 'Lazer', 'type' => 'despesa'],
            ['icon' => '🎮', 'color' => '#4dd0e1']
        );

        $user->categories()->firstOrCreate(
            ['name' => 'Educação', 'type' => 'despesa'],
            ['icon' => '📚', 'color' => '#aed581']
        );

        $user->categories()->firstOrCreate(
            ['name' => 'Salário', 'type' => 'receita'],
            ['icon' => '💼', 'color' => '#22c98a']
        );

        $user->categories()->firstOrCreate(
            ['name' => 'Freelance', 'type' => 'receita'],
            ['icon' => '💻', 'color' => '#3a9df8']
        );
    }
}
