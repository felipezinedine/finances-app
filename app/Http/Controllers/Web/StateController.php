<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Accounts;
use App\Models\Categories;
use App\Models\Goals;
use App\Models\Investments;
use App\Models\Invoices;
use App\Models\Transactions;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class StateController extends Controller
{
    public function storeTransaction(Request $request)
    {
        $data = $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'date' => 'required|date',
            'type' => 'required|in:receita,despesa,investimento',
            'account_id' => 'nullable|exists:accounts,id',
            'category_id' => 'nullable|exists:categories,id',
            'note' => 'nullable|string|max:1000',
            'recurrence' => 'nullable|in:none,recurring,installment',
            'installments' => 'nullable|integer|min:2|max:60',
        ]);

        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $this->createTransactions($user, $data);

        return response()->json($this->buildState($user));
    }

    public function storeInvoice(Request $request)
    {
        $data = $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'due_date' => 'required|date',
            'status' => 'required|in:pendente,pago,vencido',
            'category_id' => 'nullable|exists:categories,id',
            'recurrence' => 'nullable|in:none,recurring,installment',
            'installments' => 'nullable|integer|min:2|max:60',
        ]);

        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $this->createInvoices($user, $data);

        return response()->json($this->buildState($user));
    }

    public function storeInvestment(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:100',
            'invested' => 'required|numeric',
            'currentValue' => 'required|numeric',
            'date' => 'required|date',
            'account_id' => 'nullable|exists:accounts,id',
        ]);

        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $investment = $user->investments()->create([
            'name' => $data['name'],
            'type' => $data['type'],
            'invested_amount' => $data['invested'],
            'current_value' => $data['currentValue'],
            'date' => $data['date'],
            'account_id' => $data['account_id'] ?? null,
        ]);

        $investment->history()->create([
            'value' => $data['currentValue'],
            'recorded_at' => $data['date'],
        ]);

        // Ajusta saldo da conta de origem (se houver)
        if (!empty($data['account_id'])) {
            $account = Accounts::find($data['account_id']);
            if ($account && $account->user_id === $user->id) {
                $account->balance -= $data['invested'];
                $account->save();
            }
        }

        return response()->json($this->buildState($user));
    }

    public function storeGoal(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'target' => 'required|numeric',
            'current' => 'required|numeric',
            'deadline' => 'nullable|date',
            'icon' => 'nullable|string|max:8',
        ]);

        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $user->goals()->create([
            'name' => $data['name'],
            'target_amount' => $data['target'],
            'current_amount' => $data['current'],
            'deadline' => $data['deadline'],
            'icon' => $data['icon'] ?? '🎯',
        ]);

        return response()->json($this->buildState($user));
    }

    public function storeAccount(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'bank' => 'nullable|string|max:255',
            'type' => 'required|in:corrente,poupanca,carteira,investimento',
            'balance' => 'required|numeric',
        ]);

        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $user->accounts()->create([
            'name' => $data['name'],
            'bank' => $data['bank'],
            'type' => $data['type'],
            'balance' => $data['balance'],
        ]);

        return response()->json($this->buildState($user));
    }

    public function storeCategory(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:despesa,receita',
            'icon' => 'nullable|string|max:8',
            'color' => 'nullable|string|max:7',
        ]);

        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $user->categories()->create([
            'name' => $data['name'],
            'type' => $data['type'],
            'icon' => $data['icon'] ?? '🏷️',
            'color' => $data['color'] ?? '#7c5cfc',
        ]);

        return response()->json($this->buildState($user));
    }

    public function updateCategory(Request $request, Categories $category)
    {
        $user = Auth::user();
        if (!$user || $category->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:despesa,receita',
            'icon' => 'nullable|string|max:8',
            'color' => 'nullable|string|max:7',
        ]);

        $category->update([
            'name' => $data['name'],
            'type' => $data['type'],
            'icon' => $data['icon'] ?? $category->icon,
            'color' => $data['color'] ?? $category->color,
        ]);

        return response()->json($this->buildState($user));
    }

    public function getState()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        return response()->json($this->buildState($user));
    }

    public function deleteCategory(Categories $category)
    {
        $user = Auth::user();
        if (!$user || $category->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $category->delete();

        return response()->json($this->buildState($user));
    }

    public function updateTransaction(Request $request, Transactions $transaction)
    {
        $user = Auth::user();
        if (!$user || $transaction->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $data = $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'date' => 'required|date',
            'type' => 'required|in:receita,despesa,investimento',
            'account_id' => 'nullable|exists:accounts,id',
            'category_id' => 'nullable|exists:categories,id',
            'note' => 'nullable|string|max:1000',
            'recurrence' => 'nullable|in:none,recurring,installment',
            'installments' => 'nullable|integer|min:2|max:60',
        ]);

        $old = [
            'amount' => $transaction->amount,
            'type' => $transaction->type,
            'account_id' => $transaction->account_id,
        ];

        $transaction->update([
            'description' => $data['description'],
            'amount' => $data['amount'],
            'type' => $data['type'],
            'date' => $data['date'],
            'account_id' => $data['account_id'] ?? null,
            'category_id' => $data['category_id'] ?? null,
            'notes' => $data['note'] ?? null,
        ]);

        $this->syncTransactionBalance($old, $transaction);

        return response()->json($this->buildState($user));
    }

    public function deleteTransaction(Transactions $transaction)
    {
        $user = Auth::user();
        if (!$user || $transaction->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $this->reverseTransactionBalance($transaction);
        $transaction->delete();

        return response()->json($this->buildState($user));
    }

    public function updateInvoice(Request $request, Invoices $invoice)
    {
        $user = Auth::user();
        if (!$user || $invoice->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $data = $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'due_date' => 'required|date',
            'status' => 'required|in:pendente,pago,vencido',
            'category_id' => 'nullable|exists:categories,id',
            'recurrence' => 'nullable|in:none,recurring,installment',
            'installments' => 'nullable|integer|min:2|max:60',
        ]);

        $invoice->update([
            'description' => $data['description'],
            'amount' => $data['amount'],
            'due_date' => $data['due_date'],
            'status' => $data['status'],
            'category_id' => $data['category_id'] ?? null,
        ]);

        return response()->json($this->buildState($user));
    }

    public function deleteInvoice(Invoices $invoice)
    {
        $user = Auth::user();
        if (!$user || $invoice->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $invoice->delete();

        return response()->json($this->buildState($user));
    }

    public function updateInvestment(Request $request, Investments $investment)
    {
        $user = Auth::user();
        if (!$user || $investment->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:100',
            'invested' => 'required|numeric',
            'currentValue' => 'required|numeric',
            'date' => 'required|date',
            'account_id' => 'nullable|exists:accounts,id',
        ]);

        $old = [
            'invested' => $investment->invested_amount,
            'account_id' => $investment->account_id,
        ];

        $investment->update([
            'name' => $data['name'],
            'type' => $data['type'],
            'invested_amount' => $data['invested'],
            'current_value' => $data['currentValue'],
            'date' => $data['date'],
            'account_id' => $data['account_id'] ?? null,
        ]);

        // Ajusta saldo das contas associadas ao investimento
        if (!empty($old['account_id'])) {
            $oldAccount = Accounts::find($old['account_id']);
            if ($oldAccount && $oldAccount->user_id === $user->id) {
                $oldAccount->balance += $old['invested'];
                $oldAccount->save();
            }
        }

        if (!empty($data['account_id'])) {
            $newAccount = Accounts::find($data['account_id']);
            if ($newAccount && $newAccount->user_id === $user->id) {
                $newAccount->balance -= $data['invested'];
                $newAccount->save();
            }
        }

        $investment->history()->create([
            'value' => $data['currentValue'],
            'recorded_at' => $data['date'],
        ]);

        return response()->json($this->buildState($user));
    }

    public function deleteInvestment(Investments $investment)
    {
        $user = Auth::user();
        if (!$user || $investment->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Reembolsa o valor investido para a conta associada (se houver)
        if ($investment->account_id) {
            $account = Accounts::find($investment->account_id);
            if ($account && $account->user_id === $user->id) {
                $account->balance += $investment->invested_amount;
                $account->save();
            }
        }

        $investment->delete();

        return response()->json($this->buildState($user));
    }

    public function updateGoal(Request $request, Goals $goal)
    {
        $user = Auth::user();
        if (!$user || $goal->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'target' => 'required|numeric',
            'current' => 'required|numeric',
            'deadline' => 'nullable|date',
            'icon' => 'nullable|string|max:8',
        ]);

        $goal->update([
            'name' => $data['name'],
            'target_amount' => $data['target'],
            'current_amount' => $data['current'],
            'deadline' => $data['deadline'],
            'icon' => $data['icon'] ?? $goal->icon,
        ]);

        return response()->json($this->buildState($user));
    }

    public function deleteGoal(Goals $goal)
    {
        $user = Auth::user();
        if (!$user || $goal->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $goal->delete();

        return response()->json($this->buildState($user));
    }

    public function updateAccount(Request $request, Accounts $account)
    {
        $user = Auth::user();
        if (!$user || $account->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'bank' => 'nullable|string|max:255',
            'type' => 'required|in:corrente,poupanca,carteira,investimento',
            'balance' => 'required|numeric',
        ]);

        $account->update([
            'name' => $data['name'],
            'bank' => $data['bank'],
            'type' => $data['type'],
            'balance' => $data['balance'],
        ]);

        return response()->json($this->buildState($user));
    }

    public function deleteAccount(Accounts $account)
    {
        $user = Auth::user();
        if (!$user || $account->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $account->delete();

        return response()->json($this->buildState($user));
    }

    protected function applyTransactionBalance(Accounts $account, string $type, float $amount, bool $reverse = false): void
    {
        $mult = $reverse ? -1 : 1;
        $delta = ($type === 'receita' ? 1 : -1) * $mult * $amount;
        $account->balance += $delta;
        $account->save();
    }

    protected function syncTransactionBalance(array $oldData, Transactions $newTransaction): void
    {
        $oldAccount = null;
        if (!empty($oldData['account_id'])) {
            $oldAccount = Accounts::find($oldData['account_id']);
        }

        $newAccount = null;
        if (!empty($newTransaction->account_id)) {
            $newAccount = Accounts::find($newTransaction->account_id);
        }

        if ($oldAccount && $newAccount && $oldAccount->id === $newAccount->id) {
            $this->applyTransactionBalance($oldAccount, $oldData['type'], $oldData['amount'], true);
            $this->applyTransactionBalance($newAccount, $newTransaction->type, $newTransaction->amount, false);
        } else {
            if ($oldAccount) {
                $this->applyTransactionBalance($oldAccount, $oldData['type'], $oldData['amount'], true);
            }
            if ($newAccount) {
                $this->applyTransactionBalance($newAccount, $newTransaction->type, $newTransaction->amount, false);
            }
        }
    }

    protected function reverseTransactionBalance(Transactions $transaction): void
    {
        if (!$transaction->account_id) {
            return;
        }

        $account = Accounts::find($transaction->account_id);
        if (!$account) {
            return;
        }

        $this->applyTransactionBalance($account, $transaction->type, $transaction->amount, true);
    }

    protected function createTransactions($user, array $data): void
    {
        $date = Carbon::parse($data['date']);

        $times = 1;
        if (($data['recurrence'] ?? 'none') === 'recurring') {
            $times = 24;
        } elseif (($data['recurrence'] ?? 'none') === 'installment') {
            $times = $data['installments'] ?? 1;
        }

        $account = null;
        if (!empty($data['account_id'])) {
            $account = $user->accounts()->find($data['account_id']);
        }

        for ($i = 0; $i < $times; ++$i) {
            $currentDate = (clone $date)->addMonths($i);
            $description = $data['description'];
            if (($data['recurrence'] ?? 'none') === 'installment') {
                $ordinal = $i + 1;
                $description = "{$data['description']} ({$ordinal}/{$times})";
            }

            $user->transactions()->create([
                'description' => $description,
                'amount' => $data['amount'],
                'type' => $data['type'],
                'date' => $currentDate->format('Y-m-d'),
                'account_id' => $data['account_id'] ?? null,
                'category_id' => $data['category_id'] ?? null,
                'notes' => $data['note'] ?? null,
            ]);

            if ($account) {
                $account->balance += $data['type'] === 'receita' ? $data['amount'] : -$data['amount'];
                $account->save();
            }
        }
    }

    protected function createInvoices($user, array $data): void
    {
        $date = Carbon::parse($data['due_date']);

        $times = 1;
        if (($data['recurrence'] ?? 'none') === 'recurring') {
            $times = 24;
        } elseif (($data['recurrence'] ?? 'none') === 'installment') {
            $times = $data['installments'] ?? 1;
        }

        for ($i = 0; $i < $times; ++$i) {
            $currentDate = (clone $date)->addMonths($i);
            $description = $data['description'];
            if (($data['recurrence'] ?? 'none') === 'installment') {
                $ordinal = $i + 1;
                $description = "{$data['description']} ({$ordinal}/{$times})";
            }

            $user->invoices()->create([
                'description' => $description,
                'amount' => $data['amount'],
                'due_date' => $currentDate->format('Y-m-d'),
                'status' => $data['status'],
                'category_id' => $data['category_id'] ?? null,
            ]);
        }
    }

    protected function buildState($user): array
    {
        return [
            'currentUser' => [
                'id' => (string) $user->id,
                'name' => $user->name,
                'lastname' => $user->lastname ?? '',
                'email' => $user->email,
            ],
            'balance' => (float) $user->accounts()->sum('balance'),
            'accounts' => $user->accounts()->get()->map(fn ($a) => [
                'id' => (string) $a->id,
                'name' => $a->name,
                'bank' => $a->bank,
                'type' => $a->type,
                'balance' => (float) $a->balance,
            ])->toArray(),
            'categories' => $user->categories()->get()->map(fn ($c) => [
                'id' => (string) $c->id,
                'name' => $c->name,
                'type' => $c->type,
                'icon' => $c->icon,
                'color' => $c->color,
            ])->toArray(),
            'transactions' => $user->transactions()
                ->orderByDesc('date')
                ->whereMonth('date', now()->month)
                ->whereYear('date', now()->year)
                ->get()
                ->map(fn ($t) => [
                    'id' => (string) $t->id,
                    'desc' => $t->description,
                    'amount' => (float) $t->amount,
                    'date' => $t->date?->format('Y-m-d'),
                    'catId' => $t->category_id ? (string) $t->category_id : null,
                    'accId' => $t->account_id ? (string) $t->account_id : null,
                    'type' => $t->type,
                    'note' => $t->notes,
                ])->toArray(),
            'invoices' => $user->invoices()->orderByDesc('due_date')->get()->map(fn ($i) => [
                'id' => (string) $i->id,
                'desc' => $i->description,
                'amount' => (float) $i->amount,
                'due' => $i->due_date?->format('Y-m-d'),
                'status' => $i->status,
                'catId' => $i->category_id ? (string) $i->category_id : null,
            ])->toArray(),
            'investments' => $user->investments()->orderByDesc('date')->get()->map(fn ($inv) => [
                'id' => (string) $inv->id,
                'name' => $inv->name,
                'type' => $inv->type,
                'invested' => (float) $inv->invested_amount,
                'currentValue' => (float) $inv->current_value,
                'date' => $inv->date?->format('Y-m-d'),
                'accId' => $inv->account_id ? (string) $inv->account_id : null,
                'history' => $inv->history()->orderByDesc('recorded_at')->get()->map(fn ($h) => [
                    'date' => $h->recorded_at?->format('Y-m-d'),
                    'value' => (float) $h->value,
                ])->toArray(),
            ])->toArray(),
            'goals' => $user->goals()->orderByDesc('deadline')->get()->map(fn ($g) => [
                'id' => (string) $g->id,
                'name' => $g->name,
                'target' => (float) $g->target_amount,
                'current' => (float) $g->current_amount,
                'deadline' => $g->deadline?->format('Y-m-d'),
                'icon' => $g->icon,
            ])->toArray(),
        ];
    }
}
