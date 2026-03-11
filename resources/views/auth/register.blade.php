@extends('layouts.master')

@push('styles') @endpush

@section('content')

  <div id="register-page" class="auth-page active">
    <div class="auth-left">
      <div class="auth-logo">
        <div class="auth-logo-icon">💸</div>
        FinFlow
      </div>
      <h1 class="auth-headline">Comece a controlar<br>seu <span>dinheiro</span></h1>
      <p class="auth-sub">Crie sua conta gratuitamente e tenha visão completa das suas finanças em minutos.</p>
      <div class="auth-features">
        <div class="auth-feat"><div class="auth-feat-icon">✅</div>Controle de receitas e despesas</div>
        <div class="auth-feat"><div class="auth-feat-icon">📈</div>Gestão de investimentos</div>
        <div class="auth-feat"><div class="auth-feat-icon">🎯</div>Metas e objetivos financeiros</div>
        <div class="auth-feat"><div class="auth-feat-icon">🏦</div>Múltiplas contas bancárias</div>
      </div>
    </div>
    
    <livewire:auth.register />
  </div>

@endsection 

@push('scripts')
  <script src="{{ asset('js/auth/register.js') }}"></script>
@endpush