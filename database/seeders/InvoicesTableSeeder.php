<?php

namespace Database\Seeders;

use App\Models\Invoices;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class InvoicesTableSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'feliipeziinedine@gmail.com')->first();
        if (! $user) {
            return;
        }

        $category = $user->categories()->where('type', 'despesa')->first();

        $user->invoices()->firstOrCreate(
            ['description' => 'Conta de luz', 'due_date' => Carbon::today()->addDays(7)],
            [
                'amount' => 190.45,
                'status' => 'pendente',
                'category_id' => $category?->id,
            ]
        );

        $user->invoices()->firstOrCreate(
            ['description' => 'Mensalidade da academia', 'due_date' => Carbon::today()->subDays(2)],
            [
                'amount' => 120.00,
                'status' => 'pago',
                'paid_at' => Carbon::today()->subDays(1),
                'category_id' => $category?->id,
            ]
        );
    }
}
