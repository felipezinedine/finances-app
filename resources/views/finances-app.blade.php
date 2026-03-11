@extends('layouts.master')

@push('styles') @endpush

@section('content')
    <div id="landing-page" class="page active">
        <nav class="landing-nav">
            <div class="landing-logo">
            <div class="sidebar-logo-icon">💸</div>
            FinFlow
            </div>
            <div style="display:flex;gap:10px">
            <a class="btn btn-ghost" href="{{ route('login') }}">Entrar</a>
            <a class="btn btn-primary" href="{{ route('register') }}">Criar Conta</a>
            </div>
        </nav>
        <div class="landing-hero">
            <div class="hero-badge">✨ Controle financeiro inteligente</div>
            <h1 class="hero-title">Domine suas<br><span>finanças</span> agora</h1>
            <p class="hero-sub">Receitas, despesas, investimentos e metas — tudo em um só lugar, com clareza total sobre seu dinheiro.</p>
            <div class="hero-cta">
            <a class="btn btn-primary" style="font-size:15px;padding:14px 32px" href="{{ route('register') }}">Começar de graça</a>
            <a class="btn btn-secondary" style="font-size:15px;padding:14px 32px" href="{{ route('login') }}">Já tenho conta</a>
            </div>
            <div class="hero-cards">
            <div class="hero-card">
                <div class="hero-card-icon">📊</div>
                <div class="hero-card-title">Dashboard Completo</div>
                <div class="hero-card-desc">Visualize saldo, receitas e despesas em tempo real</div>
            </div>
            <div class="hero-card">
                <div class="hero-card-icon">📈</div>
                <div class="hero-card-title">Investimentos</div>
                <div class="hero-card-desc">Acompanhe sua carteira com atualização de valores</div>
            </div>
            <div class="hero-card">
                <div class="hero-card-icon">🎯</div>
                <div class="hero-card-title">Metas Financeiras</div>
                <div class="hero-card-desc">Defina objetivos e acompanhe seu progresso</div>
            </div>
            <div class="hero-card">
                <div class="hero-card-icon">🗂️</div>
                <div class="hero-card-title">Categorias</div>
                <div class="hero-card-desc">Organize transações com categorias personalizadas</div>
            </div>
            </div>
        </div>
    </div>
@endsection 

@push('scripts') @endpush