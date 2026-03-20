<?php

namespace Database\Seeders;

use App\Models\InvestmentHistory;
use App\Models\Investments;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class InvestmentHistoriesTableSeeder extends Seeder
{
    public function run(): void
    {
        $investment = Investments::where('name', 'Fundo de Emergência')->first();
        if ($investment) {
            InvestmentHistory::firstOrCreate(
                ['investment_id' => $investment->id, 'recorded_at' => Carbon::today()->subMonths(6)],
                ['value' => 8000.00]
            );

            InvestmentHistory::firstOrCreate(
                ['investment_id' => $investment->id, 'recorded_at' => Carbon::today()->subMonths(3)],
                ['value' => 8100.00]
            );

            InvestmentHistory::firstOrCreate(
                ['investment_id' => $investment->id, 'recorded_at' => Carbon::today()->subDays(5)],
                ['value' => 8200.00]
            );
        }

        $investment2 = Investments::where('name', 'Ações - Empresa X')->first();
        if ($investment2) {
            InvestmentHistory::firstOrCreate(
                ['investment_id' => $investment2->id, 'recorded_at' => Carbon::today()->subMonths(8)],
                ['value' => 2200.00]
            );

            InvestmentHistory::firstOrCreate(
                ['investment_id' => $investment2->id, 'recorded_at' => Carbon::today()->subMonths(4)],
                ['value' => 2300.00]
            );

            InvestmentHistory::firstOrCreate(
                ['investment_id' => $investment2->id, 'recorded_at' => Carbon::today()->subDays(10)],
                ['value' => 2450.00]
            );
        }
    }
}
