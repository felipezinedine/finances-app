<?php

namespace App\Observers;

use App\Models\Accounts;
use App\Models\Categories;
use App\Models\User;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        $categories = [

            [
                'name' => 'Alimentação',
                'type' => 'despesa',
                'icon' => '🍔',
                'color' => '#f5b942',
            ],
            [
                'name' => 'Transporte',
                'type' => 'despesa',
                'icon' => '🚗',
                'color' => '#f04060',
            ],
            [
                'name' => 'Moradia',
                'type' => 'despesa',
                'icon' => '🏠',
                'color' => '#7c5cfc',
            ],
            [
                'name' => 'Saúde',
                'type' => 'despesa',
                'icon' => '💊',
                'color' => '#f06292',
            ],
            [
                'name' => 'Lazer',
                'type' => 'despesa',
                'icon' => '🎮',
                'color' => '#4dd0e1',
            ],
            [
                'name' => 'Educação',
                'type' => 'despesa',
                'icon' => '📚',
                'color' => '#aed581',
            ],
            [
                'name' => 'Salário',
                'type' => 'receita',
                'icon' => '💼',
                'color' => '#22c98a',
            ],
            [
                'name' => 'Freelance',
                'type' => 'receita',
                'icon' => '💻',
                'color' => '#3a9df8',
            ],

        ];

        foreach ($categories as $category) {
            Categories::create([
                'user_id' => $user->id,
                ...$category
            ]);
        }
    }
    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
