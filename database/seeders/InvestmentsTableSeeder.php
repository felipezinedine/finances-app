<?php

namespace Database\Seeders;

use App\Models\Investments;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class InvestmentsTableSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'feliipeziinedine@gmail.com')->first();
        if (! $user) {
            return;
        }

        $user->investments()->firstOrCreate(
            ['name' => 'Fundo de Emergência'],
            [
                'type' => 'renda-fixa',
                'invested_amount' => 8000.00,
                'current_value' => 8200.00,
                'date' => Carbon::today()->subMonths(6),
            ]
        );

        $user->investments()->firstOrCreate(
            ['name' => 'Ações - Empresa X'],
            [
                'type' => 'renda-variavel',
                'invested_amount' => 2200.00,
                'current_value' => 2450.00,
                'date' => Carbon::today()->subMonths(8),
            ]
        );
    }
}
