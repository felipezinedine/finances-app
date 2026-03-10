<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <title>FinFlow — Controle Financeiro</title>

        <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,300&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('css/finances-init.css') }}">
        @stack('styles')
    </head>
<body>
    <div id="toast"></div>

    @auth
        <div class="app-layout" id="app-layout">
            @include('inc.sidebar')

            <div class="main-content">
                <div class="topbar">
                    <div class="topbar-title" id="topbar-title">@yield('title-content', 'Dashboard')</div>
                    <div class="topbar-right">
                        <button class="btn btn-primary btn-sm" onclick="openModal('modal-transaction')">+ Nova Transação</button>
                    </div>
                </div>

                <div class="content">
                    @yield('section')
                </div>
            </div>
        </div>
    @endauth
    <!-- LANDING PAGE -->
    @include('pages.lda.main')

    <!-- REGISTER PAGE -->
    @include('auth.register')

    <!-- LOGIN PAGE -->
    @include('auth.login')

    <!-- APP LAYOUT -->
    @include('demo.demo')

<script src="{{ asset('js/finances-init.js') }}"></script>
@stack('scripts')

</body>
</html>