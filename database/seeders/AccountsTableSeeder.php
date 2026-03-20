<?php

namespace Database\Seeders;

use App\Models\Accounts;
use App\Models\User;
use Illuminate\Database\Seeder;

class AccountsTableSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'feliipeziinedine@gmail.com')->first();
        if (! $user) {
            return;
        }

        $user->accounts()->firstOrCreate(
            ['name' => 'Conta corrente'],
            ['bank' => 'Banco Exemplo', 'type' => 'corrente', 'balance' => 2500.00]
        );

        $user->accounts()->firstOrCreate(
            ['name' => 'Poupança'],
            ['type' => 'poupanca', 'balance' => 12000.50]
        );

        $user->accounts()->firstOrCreate(
            ['name' => 'Carteira'],
            ['type' => 'carteira', 'balance' => 350.75]
        );

        $user->accounts()->firstOrCreate(
            ['name' => 'Investimentos'],
            ['type' => 'investimento', 'balance' => 18000.00]
        );
    }
}
