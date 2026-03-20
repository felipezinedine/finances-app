<?php

namespace Database\Seeders;

use App\Models\Transactions;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class TransactionsTableSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'feliipeziinedine@gmail.com')->first();
        if (! $user) {
            return;
        }

        $account = $user->accounts()->first();
        $categories = $user->categories()->get()->keyBy('name');

        if (! $account) {
            return;
        }

        $transactions = [
            // receitas
            ['description' => 'Salário', 'type' => 'receita', 'category' => 'Salário', 'amount' => 4500.00, 'date' => Carbon::today()->subDays(5), 'notes' => 'Pago mensal'],
            ['description' => 'Freelance UX', 'type' => 'receita', 'category' => 'Freelance', 'amount' => 1200.00, 'date' => Carbon::today()->subDays(12), 'notes' => 'Projeto entregue'],

            // despesas
            ['description' => 'Supermercado', 'type' => 'despesa', 'category' => 'Alimentação', 'amount' => 150.75, 'date' => Carbon::today()->subDays(3), 'notes' => 'Compra mensal de mantimentos'],
            ['description' => 'Uber', 'type' => 'despesa', 'category' => 'Transporte', 'amount' => 42.90, 'date' => Carbon::today()->subDays(4), 'notes' => 'Ida ao trabalho'],
            ['description' => 'Netflix + Spotify', 'type' => 'despesa', 'category' => 'Lazer', 'amount' => 59.90, 'date' => Carbon::today()->subDays(7), 'notes' => 'Assinaturas mensais'],
            ['description' => 'Remédio', 'type' => 'despesa', 'category' => 'Saúde', 'amount' => 39.90, 'date' => Carbon::today()->subDays(10), 'notes' => 'Farmácia'],
            ['description' => 'Curso online', 'type' => 'despesa', 'category' => 'Educação', 'amount' => 199.00, 'date' => Carbon::today()->subDays(14), 'notes' => 'Plataforma de cursos'],
            ['description' => 'Aluguel', 'type' => 'despesa', 'category' => 'Moradia', 'amount' => 1200.00, 'date' => Carbon::today()->subDays(8), 'notes' => 'Aluguel mensal'],
        ];

        foreach ($transactions as $tx) {
            $category = $categories->get($tx['category']);
            if (! $category) {
                continue;
            }

            $user->transactions()->firstOrCreate(
                [
                    'description' => $tx['description'],
                    'date' => $tx['date'],
                ],
                [
                    'account_id' => $account->id,
                    'category_id' => $category->id,
                    'amount' => $tx['amount'],
                    'type' => $tx['type'],
                    'notes' => $tx['notes'],
                ]
            );
        }
    }
}
