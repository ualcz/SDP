<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SDP - IFBA Seabra')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
</head>

<body class="flex flex-col min-h-screen">

    <header class="site-header">
        <div class="header-inner">

            {{-- Logotipo --}}
            <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('requerimentos.aluno') }}" class="header-brand">
                <img src="{{ asset('img/logoVertical.png') }}" alt="Logo IFBA">
                <div class="header-brand-text">
                    <span class="header-brand-title">SDP</span>
                    <span class="header-brand-subtitle">Sistema de Protocolos</span>
                </div>
            </a>

            {{-- Navegação + Usuário --}}
            <div style="display:flex; align-items:center; gap:4px;">

                <nav class="header-nav">
                    @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}"
                       class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <span>Painel administrativo</span>
                    </a>

                    <a href="{{ route('admin.consultar-requerimentos') }}"
                       class="nav-link {{ request()->routeIs('admin.consultar-requerimentos') ? 'active' : '' }}">
                        <span>Consultar requerimentos</span>

                    <a href="{{ route('admin.modelos.index') }}"
                       class="nav-link {{ request()->routeIs('admin.modelos.*') ? 'active' : '' }}">
                        <span>Modelos & Assuntos</span>
                    </a>
                    @else
                    <a href="{{ route('requerimentos.aluno.novo') }}"
                       class="nav-link {{ request()->routeIs('requerimentos.aluno.novo') ? 'active' : '' }}">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span>Novo Requerimento</span>
                    </a>

                    <a href="{{ route('requerimentos.aluno.meusRequerimentos') }}"
                       class="nav-link {{ request()->routeIs('requerimentos.aluno.meusRequerimentos') ? 'active' : '' }}">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span>Meus Requerimentos</span>
                    </a>
                    @endif
                </nav>

                <div class="header-user">
                    <span class="header-username">{{ explode(' ', auth()->user()->nome)[0] }}</span>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-logout">
                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Sair
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </header>

    <main class="flex-1 container" style="padding-top: 32px;">
        @yield('content')
    </main>

    <footer class="site-footer">
        <div class="footer-inner">

            <div class="footer-brand">
                <img src="{{ asset('img/logoVertical.png') }}" alt="Logo IFBA" class="footer-logo">
                <div>
                    <div class="footer-brand-name">SDP</div>
                    <div class="footer-brand-sub">Sistema de Protocolos e Requerimentos</div>
                </div>
            </div>

            <div class="footer-links">
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="footer-link">Painel administrativo</a>
                @else
                    <a href="{{ route('requerimentos.aluno.novo') }}" class="footer-link">Novo Requerimento</a>
                    <a href="{{ route('requerimentos.aluno.meusRequerimentos') }}" class="footer-link">Meus Requerimentos</a>
                @endif
            </div>

        </div>

        <div class="footer-bottom">
            SDP &mdash; IFBA Campus Seabra &copy; {{ date('Y') }}
        </div>
    </footer>

</body>

</html>
