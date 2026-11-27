@extends('layouts.app')

@section('title', 'Novo Requerimento - SDP IFBA')
@section('tag', 'Aluno')

@section('content')

<link rel="stylesheet" href="{{ asset('css/home.css') }}">
<link rel="stylesheet" href="{{ asset('css/form.css') }}?v={{ filemtime(public_path('css/form.css')) }}">

{{-- ===== SELEÇÃO DE SETORES (primeira coisa que o visitante vê) ===== --}}
@if (!empty($modelos))
<section id="inicio" class="fade-up" style="padding-bottom: 0; padding-top: 12px;">
    <div style="width:100%; padding: 0 8px; box-sizing: border-box;">

        <div class="cardapio-container" style="border: none; box-shadow: none; padding: 0;">
            <div class="cardapio-grid">
                @foreach($modelos as $mod)
                    @if (!empty($mod['assuntos_detalhes']) && count($mod['assuntos_detalhes']) > 0)
                        @php $cardId = 'acordeao-' . $mod['id']; @endphp
                        <div class="cardapio-card home-card-acordeao" id="card-home-{{ $mod['id'] }}">

                            {{-- Cabeçalho clicável (sigla + seta) --}}
                            <button type="button"
                                    class="home-acordeao-toggle"
                                    onclick="toggleAcordeaoHome('{{ $cardId }}', this)"
                                    aria-expanded="false"
                                    aria-controls="{{ $cardId }}">
                                <span class="cardapio-sigla">{{ $mod['setor_sigla'] ?? 'SETOR' }}</span>
                                <svg class="home-acordeao-seta" width="16" height="16" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2.5"
                                     stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="6 9 12 15 18 9"/>
                                </svg>
                            </button>

                            {{-- Conteúdo recolhível: lista + botão --}}
                            <div class="home-acordeao-corpo" id="{{ $cardId }}">
                                <div class="cardapio-lista-wrapper" style="margin-top: 10px;">
                                    <strong class="cardapio-label">Opções disponíveis:</strong>
                                    <ul class="cardapio-lista">
                                        @foreach($mod['assuntos_detalhes'] as $assunto)
                                            <li class="cardapio-item">
                                                <span class="cardapio-bullet">•</span>
                                                <span class="cardapio-texto-assunto">
                                                    {{ is_array($assunto) ? ($assunto['descricao'] ?? $assunto['titulo'] ?? 'Assunto') : $assunto }}
                                                </span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <a href="{{ route('login', ['redirect' => route('requerimentos.aluno.novo', ['setor' => $mod['id']])]) }}"
                                   class="cardapio-btn" style="margin-top: 12px;">
                                    Acessar →
                                </a>
                            </div>

                        </div>
                    @endif
                @endforeach
            </div>
        </div>

    </div>
</section>
@else
{{-- Fallback: hero clássico quando não há modelos --}}
<section id="inicio" class="fade-up">
    <div class="container">
        <div class="hero-wrapper">
            <div class="hero-text">
                <div class="title">
                    <h1>Sistema de Requerimentos e Protocolos</h1>
                    <h2>IFBA - Campus Seabra</h2>
                </div>
                <p>
                    O Sistema de Requerimentos e Protocolos (SDP) foi desenvolvido para facilitar o envio, tramitação e acompanhamento de solicitações acadêmicas e administrativas, garantindo transparência e agilidade ao processo.
                </p>
                <div class="hero-actions">
                    <a href="{{ route('login') }}" class="btn-primary">
                        Acessar Sistema →
                    </a>
                </div>
            </div>
            <div class="hero-image">
                <img src="{{ asset('img/home.webp') }}" alt="Página Inicial SDP">
            </div>
        </div>
    </div>
</section>
@endif

{{-- ===== SEÇÕES ORIGINAIS DA HOME ===== --}}
<section id="funcionalidades" class="fade-up">
    <div class="container">
        <h2>Funcionalidades do SDP</h2>
        <div class="cards-grid">
            <div class="card">
                <h3>Abertura de Requerimento</h3>
                <p>Abra chamados e requerimentos acadêmicos de forma simples e 100% digital.</p>
            </div>

            <div class="card">
                <h3>Preenchimento Automático</h3>
                <p>Faça login no SUAP para preencher automaticamente as informações do requerimento.</p>
            </div>

            <div class="card">
                <h3>Emissão de Documentos</h3>
                <p>Gere comprovantes e requerimentos oficiais prontos para impressão em formato PDF.</p>
            </div>
        </div>
    </div>
</section>

<section id="sobre" class="fade-up">
    <div class="container">
        <div class="sobre-wrapper">
            <div class="sobre-text">
                <span class="subtitle">Sobre o sistema</span>
                <h2>Como funciona a tramitação</h2>

                <p>
                    O SDP conecta os estudantes diretamente aos setores responsáveis (como <strong>CORES</strong>, <strong>Coordenações de Curso</strong> e <strong>Direção</strong>). Cada solicitação segue um fluxo organizado e transparente, gerando um registro e número de protocolo único.
                </p>

                <div class="suap-notice">
                    <span class="notice-title">Integração com o SUAP</span>
                    <p>
                        Seus dados são preenchidos automaticamente via integração. Mantenha suas informações sempre atualizadas no SUAP para evitar divergências nos requerimentos.
                    </p>
                </div>
            </div>

            <div class="sobre-image">
                <img loading="lazy" src="{{ asset('img/home_sistema.webp') }}" alt="Ilustração do sistema SDP" class="fade-right">
            </div>
        </div>
    </div>
</section>

<section id="devs" class="fade-up">
    <div class="container">
        <h2>Desenvolvedores</h2>
        <div class="devs-grid">
            <div class="card-dev">
                <div class="avatar">
                    <img loading="lazy" src="{{ asset('img/perfil/caio.webp') }}" alt="Caio Souza dos Anjos">
                </div>
                <h3>Caio Souza dos Anjos</h3>
                <p class="role">Discente IFBA</p>
                <p class="desc">Discente da graduação em ADS, ingresso no 1º semestre de 2026.</p>
            </div>
            <div class="card-dev">
                <div class="avatar">
                    <img loading="lazy" src="{{ asset('img/perfil/Clau.jpg') }}" alt="Claudeilson Souza Assuncão">
                </div>
                <h3>Claudeilson Souza Assuncão</h3>
                <p class="role">Discente IFBA</p>
                <p class="desc">Discente da graduação em ADS, ingresso no 1º semestre de 2026.</p>
            </div>
            <div class="card-dev">
                <div class="avatar">
                    <img loading="lazy" src="{{ asset('img/perfil/graziele.webp') }}" alt="Graziele Brandão Silva">
                </div>
                <h3>Graziele Brandão Silva</h3>
                <p class="role">Discente IFBA</p>
                <p class="desc">Discente da graduação em ADS, ingresso no 1º semestre de 2026.</p>
            </div>
            <div class="card-dev">
                <div class="avatar">
                    <img loading="lazy" src="{{ asset('img/perfil/larissa.jpg') }}" alt="Larissa Souza Rocha">
                </div>
                <h3>Larissa Souza Rocha</h3>
                <p class="role">Discente IFBA</p>
                <p class="desc">Discente da graduação em ADS, ingresso no 1º semestre de 2026.</p>
            </div>

            <div class="card-dev">
                <div class="avatar">
                    <img loading="lazy" src="{{ asset('img/perfil/perfil_Charles.webp') }}" alt="Monck Charles Albuquerque">
                </div>
                <h3>Monck Charles Albuquerque</h3>
                <p class="role">Docente / Orientador</p>
                <p class="desc">Docente do Curso de Informática no IFBA - Campus Seabra e orientador do projeto.</p>
            </div>
        </div>
    </div>
</section>

@if (session('error'))
    <div id="modal-error" class="modal" onclick="closeModal('modal-error')">
        <div class="modal-box">
            <div class="icon-circle bg-error">
                <img loading="lazy" src="{{ asset('img/icones_claros/x.png') }}" alt="Erro">
            </div>
            <h2>Algo deu errado!</h2>
            <p>{{ session('error') }}</p>
        </div>
    </div>
@endif

@if (session('success'))
    <div id="modal-success" class="modal" onclick="closeModal('modal-success')">
        <div class="modal-box">
            <div class="icon-circle bg-success">
                <img loading="lazy" src="{{ asset('img/icones_claros/check.png') }}" alt="Sucesso">
            </div>
            <h2>Sucesso!</h2>
            <p>{{ session('success') }}</p>
        </div>
    </div>
@endif

<script src="{{ asset('js/home.js') }}"></script>

@endsection
