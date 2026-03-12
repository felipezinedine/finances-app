<?php

use Livewire\Component;

new class extends Component
{
    public string $email;
    public string $password;

    public function login () 
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required'
        ], [
            'email.required' => 'O campo de e-mail é obrigatório.',
            'email.email' => 'Por favor, insira um e-mail válido.',
            'password.required' => 'O campo de senha é obrigatório.',
        ]);

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            session()->flash('success', 'Login bem-sucedido!');

            $this->dispatch('toast', [
                'message' => 'Login realizado com sucesso 🚀',
                'type' => 'success'
            ]);
            
            return redirect()->intended('/dashboard');
        } else {
            $this->dispatch('toast', [
                'message' => 'Credenciais inválidas. Tente novamente.',
                'type' => 'error'
            ]);
        }
    }
};
?>

<div class="auth-right">
    <h2 class="auth-title">Entrar na conta</h2>
    <p class="auth-desc">Informe seu e-mail e senha para continuar</p>
    <div id="login-alert" style="display:none"></div>
    <div class="form-group">
    <label class="form-label">E-mail</label>
    <input class="form-input @error('email') is-invalid @enderror" wire:model="email" id="login-email" type="email" placeholder="joao@email.com">
    @error('email')
        <div class="input-message">{{ $message }}</div>
    @enderror
    </div>
    <div class="form-group">
    <label class="form-label">Senha</label>
    <input class="form-input @error('password') is-invalid @enderror" wire:model="password" id="login-pass" type="password" placeholder="••••••••">
    @error('password')
        <div class="input-message">{{ $message }}</div>
    @enderror
    </div>
    <a class="btn btn-primary btn-full" wire:click="login">Entrar</a>
    <div class="auth-link-row">Não tem conta? <a class="auth-link" href="{{ route('register') }}">Criar agora</a></div>
    <div class="auth-link-row" style="margin-top:8px"><a class="auth-link" href="{{ route('demo') }}">Entrar com conta demo →</a></div>
</div>