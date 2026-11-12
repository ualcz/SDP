<!-- Etapa 1: Dados do Requerimento (Identificação, Objeto e Justificativa) -->
<div class="form-step" data-step="1">
    <!-- 1. Identificação do Aluno -->
    <fieldset>
        <legend>Identificação do Aluno</legend>
        <div class="form-linha">
            <div class="campo">
                <label>Nome:</label>
                <input type="text" value="{{ auth()->user()->nome }}" readonly>
            </div>
            <div class="campo">
                <label>Matrícula:</label>
                <input type="text" value="{{ auth()->user()->matricula ?? '' }}" readonly>
            </div>
            <div class="campo">
                <label>Turma / Curso:</label>
                <input type="text" value="{{ auth()->user()->turma_codigo ?? '' }}" readonly>
            </div>
        </div>

        <div class="info-aluno">
            <div class="form-linha">
                <div class="campo">
                    <label>E-mail Pessoal (editável):</label>
                    <input type="email" name="email_pessoal" value="{{ old('email_pessoal', auth()->user()->email_pessoal ?? '') }}" placeholder="seu.email@exemplo.com">
                </div>
                <div class="campo">
                    <label>Telefone / WhatsApp (editável):</label>
                    <input type="text" name="telefone" value="{{ old('telefone', auth()->user()->telefone ?? '') }}" placeholder="(XX) XXXXX-XXXX">
                </div>
            </div>

            <div class="form-linha">
                <div class="campo" style="flex: 2; min-width: 240px;">
                    <label>Rua e Número (editável):</label>
                    <input type="text" name="rua" value="{{ old('rua', auth()->user()->endereco?->rua ?? '') }}" placeholder="Ex: Rua Antonio Francisco, 60">
                </div>
                <div class="campo" style="flex: 1.5; min-width: 150px;">
                    <label>Bairro:</label>
                    <input type="text" name="bairro" value="{{ old('bairro', auth()->user()->endereco?->bairro ?? '') }}" placeholder="Bairro">
                </div>
            </div>

            <div class="form-linha">
                <div class="campo" style="flex: 2; min-width: 180px;">
                    <label>Cidade:</label>
                    <input type="text" name="cidade" value="{{ old('cidade', auth()->user()->endereco?->cidade ?? '') }}" placeholder="Cidade">
                </div>
                <div class="campo" style="flex: 0.8; min-width: 80px;">
                    <label>Estado (UF):</label>
                    <input type="text" name="estado" maxlength="2" value="{{ old('estado', auth()->user()->endereco?->estado ?? '') }}" placeholder="BA" style="text-transform: uppercase;">
                </div>
                <div class="campo" style="flex: 1.2; min-width: 130px;">
                    <label>CEP:</label>
                    <input type="text" name="cep" value="{{ old('cep', auth()->user()->endereco?->cep ?? '') }}" placeholder="00000-000">
                </div>
            </div>
        </div>
        <button type="button" id="ver-info-aluno" onclick="verInfoAluno()" class="inline-flex justify-center items-center w-10 h-10">
            <img src="{{ asset('img/icons/chevron-down.svg') }}" class="icon">
        </button>
    </fieldset>

    <div class="cardapio-container">
        <h1 class="cardapio-titulo">Selecione o Setor</h1>
            <div class="cardapio-grid">
                @foreach($modelos as $mod)
                    @if (!empty($mod['assuntos_detalhes']) && count($mod['assuntos_detalhes']) > 0)
                        <div class="cardapio-card">
                            <div class="cardapio-card-content">
                                <!-- Cabeçalho do Card -->
                                <div class="cardapio-header">
                                    <span class="cardapio-sigla">
                                        {{ $mod['setor_sigla'] ?? 'SETOR' }}
                                    </span>
                                </div>

                                {{-- <p class="cardapio-titulo-setor">
                                    {{ $mod['titulo'] ?? 'Requerimentos Gerais' }}
                                </p> --}}

                                <!-- Lista de Assuntos -->
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

                            <!-- Botão de Ação -->
                            <a href="{{ ($isVisualizacaoAdmin ?? false) ? route('admin.setores.preview-aluno', $mod['id']) : route('requerimentos.aluno.novo', ['setor' => $mod['id']]) }}"
                            class="cardapio-btn {{ ($modeloChave ?? '') == $mod['id'] ? 'active' : '' }}">
                                {{ ($modeloChave ?? '') == $mod['id'] ? 'Setor Selecionado' : 'Acessar Requerimento' }}
                            </a>
                        </div>
                    @endif
                @endforeach
            </div>
    </div>


    <button type="button" class="btn-enviar" onclick="mudarPasso(2)" style="margin-top: 10px;">Próximo</button>
</div>
