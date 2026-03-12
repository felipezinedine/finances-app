@extends('layouts.master')

@push('styles') @endpush

@section('content')

  <div id="login-page" class="auth-page active">
    <div class="auth-left">
      <div class="auth-logo">
        <div class="auth-logo-icon">💸</div>
        FinFlow
      </div>
      <h1 class="auth-headline">Bem-vindo<br>de <span>volta</span></h1>
      <p class="auth-sub">Continue de onde parou. Suas finanças estão esperando por você.</p>
      <div class="auth-features">
        <div class="auth-feat"><div class="auth-feat-icon">🔒</div>Seus dados são privados e seguros</div>
        <div class="auth-feat"><div class="auth-feat-icon">⚡</div>Acesso instantâneo ao dashboard</div>
        <div class="auth-feat"><div class="auth-feat-icon">📱</div>Funciona em qualquer dispositivo</div>
      </div>
    </div>
    <livewire:auth.login />
  </div>

@endsection 

@push('scripts')
  <script src="{{ asset('js/auth/login.js') }}"></script>
@endpush