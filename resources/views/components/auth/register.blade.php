<?php

use Livewire\Component;

new class extends Component
{
    public $name;
    public $lastname;
    public $email;
    public $password;
    public $balance;

    public function register()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|min:5',
            'balance' => 'nullable|min:0',
        ], [
          'name.required' => 'O campo nome é obrigatório.',
          'lastname.required' => 'O campo sobrenome é obrigatório.',
          'email.required' => 'O campo e-mail é obrigatório.',
          'email.email' => 'O campo e-mail deve ser um endereço de e-mail válido.',
          'email.unique' => 'Este e-mail já está registrado.',
          'password.required' => 'O campo senha é obrigatório.',
          'password.min' => 'A senha deve conter no mínimo 5 caracteres.',
          'balance.min' => 'O saldo inicial não pode ser negativo.',
        ]);

        $user = \App\Models\User::create([
            'name' => $this->name . ' ' . $this->lastname,
            'email' => $this->email,
            'password' => bcrypt($this->password),
        ]);

        if ($user) {
            $account = App\Models\Accounts::create([
                'user_id' => $user->id,
                'name' => 'Conta Principal',
                'type' => 'corrente',
                'balance' => toFloat($this->balance) ?? 0,
            ]);

            auth()->login($user);
            $this->dispatch('toast', [
                'message' => 'Login realizado com sucesso 🚀',
                'type' => 'success'
            ]);
            return redirect()->route('dashboard');
        } else {
            $this->dispatch('toast', [
                'message' => 'Ocorreu um erro ao criar a conta. Tente novamente.',
                'type' => 'error'
            ]);
        }
    }

    public function render()
    {
        return view('components.auth.register');
    }
};
?>

<div>
    <form wire:submit.prevent="register">
      <div class="auth-right">
        <h2 class="auth-title">Criar conta</h2>
        <p class="auth-desc">Preencha as informações abaixo para começar</p>
        <div id="reg-alert" style="display:none"></div>
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Nome</label>
            <input class="form-input @error('name') input-error @enderror" id="reg-name" wire:model.defer="name" type="text" placeholder="João">
            @error('name')
                <span class="input-message">{{ $message }}</span>
            @enderror
          </div>
          <div class="form-group">
            <label class="form-label">Sobrenome</label>
            <input class="form-input @error('lastname') input-error @enderror" id="reg-lastname" wire:model.defer="lastname" type="text" placeholder="Silva">
            @error('lastname')
                <span class="input-message">{{ $message }}</span>
            @enderror
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">E-mail</label>
          <input class="form-input @error('email') input-error @enderror" id="reg-email" wire:model.defer="email" type="email" placeholder="joao@email.com">
          @error('email')
              <span class="input-message">{{ $message }}</span>
          @enderror
        </div>
        <div class="form-group">
          <label class="form-label">Senha</label>
          <input class="form-input @error('password') input-error @enderror" id="reg-pass" wire:model.defer="password" type="password" placeholder="••••••••">
          @error('password')
              <span class="input-message">{{ $message }}</span>
          @enderror
        </div>
        <div class="form-group">
          <label class="form-label">Saldo inicial</label>
          <input class="form-input money @error('balance') input-error @enderror" id="reg-balance" wire:model.defer="balance" type="text" placeholder="0,00">
          @error('balance')
              <span class="input-message">{{ $message }}</span>
          @enderror
        </div>
        <button class="btn btn-primary btn-full" wire:click="register">Criar conta grátis</button>
        <div class="auth-link-row">Já tem conta? <a class="auth-link" href="{{ route('login') }}">Entrar</a></div>
      </div>
    </form>
</div>