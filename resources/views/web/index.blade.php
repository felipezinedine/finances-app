@extends('layouts.master')

@push('styles') @endpush

@section('section')
    <!-- DASHBOARD -->
    @include('web.inc.dashboard')

    <!-- TRANSACTIONS -->
    @include('web.inc.transactions')

    <!-- INVOICES -->
    @include('web.inc.invoices')

    <!-- INVESTMENTS -->
    @include('web.inc.investments')

    <!-- GOALS -->
    @include('web.inc.goals')

    <!-- ACCOUNTS -->
    @include('web.inc.accounts')

    <!-- CATEGORIES -->
    @include('web.inc.categories')

    <!-- REPORTS -->
    @include('web.inc.reports')
@endsection

@push('scripts')
    @php
        $user = auth()->user();
        $initialState = null;

        if ($user) {
            $initialState = [
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
                'transactions' => $user->transactions()->orderByDesc('date')->get()->map(fn ($t) => [
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
    @endphp

    @if ($initialState)
        <script>
            window.initialState = @json($initialState);
        </script>
    @endif

    <script src="{{ asset('js/finances-init.js') }}"></script>
    <script src="{{ asset('js/web-init.js') }}"></script>
@endpush