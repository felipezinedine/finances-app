<?php

/*
|--------------------------------------------------------------------------
| USUÁRIO / AUTENTICAÇÃO
|--------------------------------------------------------------------------
*/

use Illuminate\Support\Facades\Auth;

if (!function_exists('user')) {
    function user()
    {
        return Auth::user();
    }
}

if (!function_exists('userId')) {
    function userId(): ?int
    {
        return Auth::check() ? Auth::id() : null;
    }
}

if (!function_exists('userName')) {
    function userName(): ?string
    {
        return Auth::check() ? user()->name : null;
    }
}

if (!function_exists('userEmail')) {
    function userEmail(): ?string
    {
        return Auth::check() ? user()->email : null;
    }
}

if (!function_exists('getInitialsUser')) {
    function getInitialsUser(): string
    {
        $name = trim(userName());
        $parts = explode(' ', $name);

        if (count($parts) > 1) {
            $first = mb_substr($parts[0], 0, 1);
            $last = mb_substr(end($parts), 0, 1);

            return strtoupper($first.$last);
        }

        return strtoupper(mb_substr($name, 0, 2));
    }
}

/*
|--------------------------------------------------------------------------
| FORMATAÇÕES FINANCEIRAS
|--------------------------------------------------------------------------
*/
if (!function_exists('formatMoney')) {
    function formatMoney($value, ?string $currency = null): string
    {
        if (is_null($currency)) {
            return number_format(floatval($value ?: 0), 2, ',', '.');
        }

        return $currency.' '.number_format(floatval($value ?: 0), 2, ',', '.');
    }
}

if (!function_exists('toFloat')) {
    function toFloat(string $valor): float
    {
        return floatval(str_replace(['.', ','], ['', '.'], $valor));
    }
}

if (!function_exists('percent')) {
    function percent(float $value, int $decimals = 2): string
    {
        return number_format($value, $decimals, ',', '.').'%';
    }
}

/*
|--------------------------------------------------------------------------
| DATA E HORA
|--------------------------------------------------------------------------
*/
if (!function_exists('dataBr')) {
    function dataBr(?string $date): ?string
    {
        return $date ? date('d/m/Y', strtotime($date)) : null;
    }
}

if (!function_exists('dataHoraBr')) {
    function dataHoraBr(?string $date): ?string
    {
        return $date ? date('d/m/Y H:i', strtotime($date)) : null;
    }
}

if (!function_exists('nowBr')) {
    function nowBr(): string
    {
        return now()->format('d/m/Y H:i');
    }
}
