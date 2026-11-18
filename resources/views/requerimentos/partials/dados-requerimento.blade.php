<!-- Etapa 1: Dados do Requerimento (Identificação, Objeto e Justificativa) -->
<div class="form-step" data-step="1">
    <!-- 1. Identificação do Aluno -->
    @if (!($isGuest ?? false))
    <fieldset>
        <legend>Identificação do Aluno</legend>

        {{-- Linha 1: Nome, Matrícula, Turma --}}
        <div class="ident-grid">
            <div class="campo ident-nome">
                <label>Nome:</label>
                <input type="text" value="{{ auth()->user()->nome }}" readonly>
            </div>
            <div class="campo ident-sm">
                <label>Matrícula:</label>
                <input type="text" value="{{ auth()->user()->matricula ?? '' }}" readonly>
            </div>
            <div class="campo ident-sm">
                <label>Turma / Curso:</label>
                <input type="text" value="{{ auth()->user()->turma_codigo ?? '' }}" readonly>
            </div>
        </div>

        <div class="info-aluno">
            {{-- Linha 2: E-mail, Telefone --}}
            <div class="ident-grid">
                <div class="campo ident-nome">
                    <label>E-mail Pessoal (editável):</label>
                    <input type="email" name="email_pessoal" value="{{ old('email_pessoal', auth()->user()->email_pessoal ?? '') }}" placeholder="seu.email@exemplo.com">
                </div>
                <div class="campo ident-sm">
                    <label>Telefone / WhatsApp (editável):</label>
                    <input type="text" name="telefone" value="{{ old('telefone', auth()->user()->telefone ?? '') }}" placeholder="(XX) XXXXX-XXXX">
                </div>
            </div>

            {{-- Linha 3: Rua, Bairro --}}
            <div class="ident-grid">
                <div class="campo ident-nome">
                    <label>Rua e Número (editável):</label>
                    <input type="text" name="rua" value="{{ old('rua', auth()->user()->endereco?->rua ?? '') }}" placeholder="Ex: Rua Antonio Francisco, 60">
                </div>
                <div class="campo ident-sm">
                    <label>Bairro:</label>
                    <input type="text" name="bairro" value="{{ old('bairro', auth()->user()->endereco?->bairro ?? '') }}" placeholder="Bairro">
                </div>
            </div>

            {{-- Linha 4: Cidade, UF, CEP --}}
            <div class="ident-grid">
                <div class="campo ident-nome">
                    <label>Cidade:</label>
                    <input type="text" name="cidade" value="{{ old('cidade', auth()->user()->endereco?->cidade ?? '') }}" placeholder="Cidade">
                </div>
                <div class="campo ident-uf">
                    <label>UF:</label>
                    <input type="text" name="estado" maxlength="2" value="{{ old('estado', auth()->user()->endereco?->estado ?? '') }}" placeholder="BA" style="text-transform: uppercase;">
                </div>
                <div class="campo ident-cep">
                    <label>CEP:</label>
                    <input type="text" name="cep" value="{{ old('cep', auth()->user()->endereco?->cep ?? '') }}" placeholder="00000-000">
                </div>
            </div>
        </div>

        <button type="button" id="ver-info-aluno" onclick="verInfoAluno()" class="inline-flex justify-center items-center w-10 h-10">
            <img src="{{ asset('img/icons/chevron-down.svg') }}" class="icon">
        </button>
    </fieldset>
    @else
    {{-- Banner para visitantes não autenticados --}}
    <div style="background: #eff6ff; border: 1px solid #bfdbfe; color: #1e40af; padding: 14px 20px; border-radius: 8px; margin-bottom: 16px; display: flex; align-items: center; gap: 12px;">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" flex-shrink="0">
            <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
        <div>
            <strong>Visualização prévia dos setores e requerimentos disponíveis.</strong>
            <span style="font-size: 0.875rem; display: block; color: #3b82f6;">Escolha um setor e clique em <em>Acessar Requerimento</em> para fazer login e enviar seu requerimento.</span>
        </div>
    </div>
    @endif

    <fieldset class="cardapio-container">
        <legend>Setores</legend>
        <h1 class="cardapio-titulo">Selecione o setor desejado</h1>
            <div class="cardapio-grid">
                @foreach($modelos as $mod)
                    @if (!empty($mod['assuntos_detalhes']) && count($mod['assuntos_detalhes']) > 0)
                        @php
                            $isSetorAtivo = ($modeloChave ?? '') == $mod['id'];
                            $urlSetor = ($isVisualizacaoAdmin ?? false)
                                ? route('admin.setores.preview-aluno', $mod['id'])
                                : route('requerimentos.aluno.novo', ['setor' => $mod['id']]);
                            $urlLogin = route('login', ['redirect' => route('requerimentos.aluno.novo', ['setor' => $mod['id']])]);
                        @endphp

                        <div class="cardapio-card {{ $isSetorAtivo ? 'cardapio-card-ativo' : '' }}" id="card-setor-{{ $mod['id'] }}">
                            <div class="cardapio-card-content">
                                <div class="cardapio-header">
                                    <span class="cardapio-sigla">{{ $mod['setor_sigla'] ?? 'SETOR' }}</span>
                                </div>
                                <div class="cardapio-lista-wrapper">
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
                            </div>

                            @if ($isGuest ?? false)
                                <a href="{{ $urlLogin }}" class="cardapio-btn">
                                    Acessar Formulário →
                                </a>
                            @else
                                <button type="button"
                                        class="cardapio-btn {{ $isSetorAtivo ? 'active' : '' }}"
                                        data-setor-id="{{ $mod['id'] }}"
                                        onclick="selecionarSetor({{ $mod['id'] }})">
                                    {{ $isSetorAtivo ? 'Setor Selecionado ✓' : 'Acessar Formulário →' }}
                                </button>
                            @endif
                        </div>
                    @endif
                @endforeach
            </div>
    </fieldset>

    {{-- Botão Próximo — só aparece quando um setor for selecionado --}}
    @if (!($isGuest ?? false))
    <button type="button"
            id="btn-proximo-setor"
            class="btn-enviar"
            onclick="mudarPasso(2)"
            style="margin-top: 14px; display: {{ ($modeloChave ?? '') ? 'block' : 'none' }};">
        Próximo →
    </button>
    @endif

    {{-- JSON com todos os setores para troca dinâmica sem reload --}}
    @if (!($isGuest ?? false))
    <script>
        window.SDP_SETORES = @json(array_values($modelos));
        window.SDP_MENOR   = @json($alunoMenor ?? false);
    </script>
    @endif

</div>
