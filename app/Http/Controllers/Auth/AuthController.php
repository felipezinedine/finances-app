<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function logout()
    {
        auth()->logout();
        session()->flush();
        session()->regenerate();

        return redirect()->route('home');
    }
}
