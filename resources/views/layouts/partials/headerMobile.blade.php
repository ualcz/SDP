<link rel="stylesheet" href="{{ asset('css/headerMobile.css') }}?v={{ filemtime(public_path('css/headerMobile.css')) }}">

<header class="site-header">
    <div class="header-inner">

        <a href="{{ auth()->check() ? (auth()->user()->isAdmin() ? route('admin.dashboard') : (auth()->user()->isServidor() ? route('servidor.dashboard') : route('requerimentos.aluno'))) : url('/') }}" class="header-brand">
            <img src="{{ asset('img/logoVertical.png') }}" alt="Logo IFBA" class="header-logo">
            <div class="header-brand-text">
                <span class="header-brand-title">SDP</span>
                <span class="header-brand-subtitle">Sistema de Protocolos</span>
            </div>
        </a>

        <button type="button" class="hamburger-btn" id="hamburgerBtn" aria-label="Abrir menu">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>

        <div class="mobile-overlay" id="mobileOverlay"></div>

        <div class="header-menu-container" id="headerMenuContainer">

            <button type="button" class="mobile-close-btn" id="closeMenuBtn" aria-label="Fechar menu">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>

            @auth
                <nav class="header-nav">
                    @if(auth()->user()->isAdmin() || auth()->user()->isServidor())
                        <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('servidor.dashboard') }}"
                           class="nav-link {{ request()->routeIs('admin.dashboard', 'servidor.dashboard') ? 'active' : '' }}">
                            <span>Dashboard</span>
                        </a>
                        <a href="{{ route('admin.consultar-requerimentos') }}"
                           class="nav-link {{ request()->routeIs('admin.consultar-requerimentos') ? 'active' : '' }}">
                            <span>Consultar requerimentos</span>
                        </a>
                    @else
                        <a href="{{ route('home') }}"
                        class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10.5L12 3l9 7.5M5 9v11h14V9M9 20v-6h6v6"/>
                            </svg>
                            <span>Home</span>
                        </a>
                        <a href="{{ route('requerimentos.aluno.novo') }}" class="nav-link {{ request()->routeIs('requerimentos.aluno.novo') ? 'active' : '' }}">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span>Novo Requerimento</span>
                        </a>
                        <a href="{{ route('requerimentos.aluno.meusRequerimentos') }}" class="nav-link {{ request()->routeIs('requerimentos.aluno.meusRequerimentos') ? 'active' : '' }}">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span>Meus Requerimentos</span>
                        </a>
                    @endif

                    {{-- Dropdown de Setores (caso responsável) --}}
                    @if(auth()->user()->ehResponsavel() && auth()->user()->setoresSobResponsabilidade->count() > 0)
                        @php
                            $setorAtivo = auth()->user()->setoresSobResponsabilidade->first(function($setor) {
                                return request()->is("setor/{$setor->id}*");
                            });
                        @endphp
                        <div class="dropdown-setores">
                            <button type="button" class="dropdown-toggle-btn {{ $setorAtivo ? 'active' : '' }}" onclick="toggleDropdownSetores(event)">
                                <span>{{ $setorAtivo ? ($setorAtivo->setor_sigla ?? $setorAtivo->nome) : 'Seus Setores' }}</span>
                                <svg class="dropdown-arrow {{ $setorAtivo ? 'rotated' : '' }}" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div class="dropdown-menu-setores" id="menuSetores">
                                @foreach(auth()->user()->setoresSobResponsabilidade as $setor)
                                    <a href="{{ route('setor.responsavel.dashboard', $setor->id) }}" class="dropdown-item {{ request()->is("setor/{$setor->id}*") ? 'active' : '' }}">
                                        <span>{{ $setor->setor_sigla ?? $setor->nome }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </nav>

                <div class="header-user">
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.setores.index') }}" class="nav-link {{ request()->routeIs('admin.setores.*', 'admin.modelos.*') ? 'active' : '' }}">
                            <span>Configurações</span>
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.index') ? 'active' : '' }}">
                            <span>Usuários</span>
                        </a>
                    @else
                        @if(auth()->user()->ehResponsavel())
                            <a href="{{ route('admin.setores.index') }}" class="nav-link {{ request()->routeIs('admin.setores.index') ? 'active' : '' }}">
                                <span>Configurações</span>
                            </a>
                        @endif
                        <a class="profile header-username nav-link {{ request()->routeIs('requerimentos.aluno') ? 'active' : '' }}" href="{{ auth()->user()->isServidor() ? route('servidor.dashboard') : route('requerimentos.aluno') }}">
                            {{ explode(' ', auth()->user()->nome)[0] }}
                        </a>
                    @endif

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-logout">Sair</button>
                    </form>
                </div>
            @endauth

            @guest
                <ul class="guest-mobile-links">
                    <li><a href="#inicio" class="nav-link scroll-link">Início</a></li>
                    <li><a href="#funcionalidades" class="nav-link scroll-link">Funcionalidades</a></li>
                    <li><a href="#sobre" class="nav-link scroll-link">Sobre</a></li>
                    <li><a href="#devs" class="nav-link scroll-link">Desenvolvedores</a></li>
                </ul>
                <div class="header-user">
                    <a class="nav-link active" href="{{ route('login') }}">Entrar</a>
                </div>
            @endguest

        </div>

    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const hamburgerBtn = document.getElementById('hamburgerBtn');
        const closeMenuBtn = document.getElementById('closeMenuBtn');
        const menuContainer = document.getElementById('headerMenuContainer');
        const overlay = document.getElementById('mobileOverlay');

        function toggleMenu() {
            menuContainer.classList.toggle('is-open');
            overlay.classList.toggle('is-open');
        }

        if (hamburgerBtn) hamburgerBtn.addEventListener('click', toggleMenu);
        if (closeMenuBtn) closeMenuBtn.addEventListener('click', toggleMenu);
        if (overlay) overlay.addEventListener('click', toggleMenu);

        document.querySelectorAll('.scroll-link').forEach(link => {
            link.addEventListener('click', () => {
                menuContainer.classList.remove('is-open');
                overlay.classList.remove('is-open');
            });
        });
    });
</script>
