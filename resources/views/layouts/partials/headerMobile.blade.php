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

            {{-- Topo do Drawer: Logo e Botão Fechar --}}
            <div class="mobile-drawer-header">
                <div class="drawer-header-brand">
                    <img src="{{ asset('img/logoVertical.png') }}" alt="IFBA" class="drawer-logo">
                    <div>
                        <span class="drawer-title">SDP</span>
                        <span class="drawer-subtitle">Campus Seabra</span>
                    </div>
                </div>
                <button type="button" class="mobile-close-btn" id="closeMenuBtn" aria-label="Fechar menu">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            @auth
                @php
                    $usuario = auth()->user();
                    $nomeCompleto = $usuario->nome ?? 'Usuário';
                    $nomes = array_values(array_filter(explode(' ', trim($nomeCompleto))));
                    $primeiroNome = $nomes[0] ?? 'Usuário';
                    $sobrenome = count($nomes) > 1 ? end($nomes) : '';
                    $iniciais = mb_strtoupper(mb_substr($primeiroNome, 0, 1) . ($sobrenome ? mb_substr($sobrenome, 0, 1) : ''));
                    $roleNome = $usuario->isAdmin() ? 'Administrador' : ($usuario->isServidor() ? 'Servidor' : 'Aluno');
                    $linkPerfil = $usuario->isServidor() ? route('servidor.dashboard') : route('requerimentos.aluno');
                @endphp

                {{-- Card de Perfil com Hierarquia Visual --}}
                <div class="mobile-user-card">
                    <div class="user-avatar-badge">{{ $iniciais }}</div>
                    <div class="user-info">
                        <div class="user-name" title="{{ $nomeCompleto }}">{{ $nomeCompleto }}</div>
                        <div class="user-meta">
                            <span class="user-role-badge role-{{ strtolower($roleNome) }}">{{ $roleNome }}</span>
                            @if(!empty($usuario->matricula))
                                <span class="user-matricula">{{ $usuario->matricula }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="menu-divider"></div>

                {{-- Seção de Navegação Principal --}}
                <div class="menu-section-label">Menu Principal</div>
                <nav class="header-nav">
                    @if($usuario->isAdmin() || $usuario->isServidor())
                        <a href="{{ $usuario->isAdmin() ? route('admin.dashboard') : route('servidor.dashboard') }}"
                           class="nav-link {{ request()->routeIs('admin.dashboard', 'servidor.dashboard') ? 'active' : '' }}">
                            <div class="nav-icon">
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                                </svg>
                            </div>
                            <span>Dashboard</span>
                        </a>
                        <a href="{{ route('admin.consultar-requerimentos') }}"
                           class="nav-link {{ request()->routeIs('admin.consultar-requerimentos') ? 'active' : '' }}">
                            <div class="nav-icon">
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <span>Consultar Requerimentos</span>
                        </a>
                    @else
                        <a href="{{ route('home') }}"
                           class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                            <div class="nav-icon">
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                            </div>
                            <span>Home</span>
                        </a>
                        <a href="{{ route('requerimentos.aluno.novo') }}" class="nav-link {{ request()->routeIs('requerimentos.aluno.novo') ? 'active' : '' }}">
                            <div class="nav-icon">
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                </svg>
                            </div>
                            <span>Novo Requerimento</span>
                        </a>
                        <a href="{{ route('requerimentos.aluno.meusRequerimentos') }}" class="nav-link {{ request()->routeIs('requerimentos.aluno.meusRequerimentos') ? 'active' : '' }}">
                            <div class="nav-icon">
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <span>Meus Requerimentos</span>
                        </a>
                    @endif

                    {{-- Setores / Gestão do Setor --}}
                    @if($usuario->ehResponsavel() && $usuario->setoresSobResponsabilidade->count() > 0)
                        @php
                            $setorAtivo = $usuario->setoresSobResponsabilidade->first(function($setor) {
                                return request()->is("setor/{$setor->id}*");
                            });
                        @endphp
                        <div class="dropdown-setores">
                            <button type="button" class="dropdown-toggle-btn {{ $setorAtivo ? 'active' : '' }}" onclick="toggleDropdownSetores(event)">
                                <div class="nav-icon">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                                <span>{{ $setorAtivo ? ($setorAtivo->setor_sigla ?? $setorAtivo->nome) : 'Seus Setores' }}</span>
                                <svg class="dropdown-arrow {{ $setorAtivo ? 'rotated' : '' }}" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div class="dropdown-menu-setores" id="menuSetores">
                                @foreach($usuario->setoresSobResponsabilidade as $setor)
                                    <a href="{{ route('setor.responsavel.dashboard', $setor->id) }}" class="dropdown-item {{ request()->is("setor/{$setor->id}*") ? 'active' : '' }}">
                                        <span>{{ $setor->setor_sigla ?? $setor->nome }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Administração do Sistema --}}
                    @if($usuario->isAdmin() || $usuario->ehResponsavel())
                        <div class="menu-divider" style="margin: 8px 0;"></div>
                        <div class="menu-section-label">Administração</div>
                        
                        <a href="{{ route('admin.setores.index') }}" class="nav-link {{ request()->routeIs('admin.setores.*', 'admin.modelos.*') ? 'active' : '' }}">
                            <div class="nav-icon">
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <span>Configurações</span>
                        </a>

                        @if($usuario->isAdmin())
                            <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.index') ? 'active' : '' }}">
                                <div class="nav-icon">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                </div>
                                <span>Usuários</span>
                            </a>
                        @endif
                    @endif
                </nav>

                {{-- Rodapé: Botão de Sair com Ícone e Estilo Moderno --}}
                <div class="mobile-drawer-footer">
                    <form action="{{ route('logout') }}" method="POST" style="width: 100%; margin: 0;">
                        @csrf
                        <button type="submit" class="btn-logout-modern">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            <span>Sair da Conta</span>
                        </button>
                    </form>
                </div>
            @endauth

            @guest
                <div class="menu-section-label">Navegação</div>
                <ul class="guest-mobile-links">
                    <li>
                        <a href="#inicio" class="nav-link scroll-link">
                            <div class="nav-icon"><svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg></div>
                            <span>Início</span>
                        </a>
                    </li>
                    <li>
                        <a href="#funcionalidades" class="nav-link scroll-link">
                            <div class="nav-icon"><svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg></div>
                            <span>Funcionalidades</span>
                        </a>
                    </li>
                    <li>
                        <a href="#sobre" class="nav-link scroll-link">
                            <div class="nav-icon"><svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                            <span>Sobre</span>
                        </a>
                    </li>
                    <li>
                        <a href="#devs" class="nav-link scroll-link">
                            <div class="nav-icon"><svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg></div>
                            <span>Desenvolvedores</span>
                        </a>
                    </li>
                </ul>
                <div class="mobile-drawer-footer">
                    <a class="btn-login-modern" href="{{ route('login') }}">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        <span>Acessar o Sistema</span>
                    </a>
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
