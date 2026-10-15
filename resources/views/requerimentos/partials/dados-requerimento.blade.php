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

    <!-- 2. Objeto do Requerimento -->
    <fieldset>
        <legend>Objeto do Requerimento</legend>
        <div class="opcoes-objeto">
            @php
                $assuntosMap = collect($modeloAtivo['assuntos_detalhes'] ?? [])->keyBy('descricao');
            @endphp
            @foreach($modeloAtivo['objetos'] ?? [] as $codigo => $descricao)
                @php
                    $assuntoItem = $assuntosMap[$descricao] ?? null;
                    $obs = $assuntoItem['observacao'] ?? null;
                    $indexAssunto = $loop->index;
                    $isChecked = old('objetoDoRequerimento') === $descricao;
                @endphp
                <label class="opcao-objeto-card {{ $isChecked ? 'opcao-selecionada' : '' }}">
                    <span class="opcao-header">
                        <input type="radio"
                               class="opcao-radio"
                               name="objetoDoRequerimento"
                               value="{{ $descricao }}"
                               onchange="alternarObjetoOutro(false); mostrarDocumentosAssunto({{ $indexAssunto }}); document.querySelectorAll('.opcao-objeto-card').forEach(el => el.classList.remove('opcao-selecionada')); this.closest('.opcao-objeto-card').classList.add('opcao-selecionada');"
                               {{ $isChecked ? 'checked' : '' }}>
                        <span class="opcao-titulo">{{ $descricao }}</span>
                        @if(!empty($assuntoItem['link_norma']))
                            <a href="{{ $assuntoItem['link_norma'] }}"
                               target="_blank"
                               rel="noopener noreferrer"
                               aria-label="Consultar norma de {{ $descricao }}"
                               title="Consultar norma"
                               onclick="event.stopPropagation()"
                               class="opcao-norma-btn">?</a>
                        @endif
                    </span>
                    @if(!empty($obs))
                        <span class="opcao-observacao">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
                            {{ $obs }}
                        </span>
                    @endif
                </label>
            @endforeach
            <label class="opcao-objeto-card {{ old('objetoDoRequerimento') === 'outro' ? 'opcao-selecionada' : '' }}">
                <span class="opcao-header">
                    <input type="radio"
                           class="opcao-radio"
                           name="objetoDoRequerimento"
                           value="outro"
                           onchange="alternarObjetoOutro(true); document.querySelectorAll('.opcao-objeto-card').forEach(el => el.classList.remove('opcao-selecionada')); this.closest('.opcao-objeto-card').classList.add('opcao-selecionada');"
                           {{ old('objetoDoRequerimento') === 'outro' ? 'checked' : '' }}>
                    <span class="opcao-titulo">Outro</span>
                </span>
            </label>
        </div>

        <div class="campo" style="margin-top: 10px;">
            <label>Outro / Detalhe adicional (opcional):</label>
                 <small id="objeto-outro-ajuda" class="ajuda-campo-bloqueado"
                     style="display: {{ old('objetoDoRequerimento') === 'outro' ? 'none' : 'flex' }}; align-items: center; gap: 5px; margin-bottom: 6px; color: #64748b; font-size: 12px; line-height: 1.4;">
                <svg aria-hidden="true" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
                Selecione “Outro” para liberar este campo.
            </small>
            <input type="text" 
                   name="objeto_outro" 
                   value="{{ old('objeto_outro') }}"
                   @disabled(old('objetoDoRequerimento') !== 'outro')
                   style="background-color: {{ old('objetoDoRequerimento') === 'outro' ? '#fff' : '#f1f5f9' }}; border-color: {{ old('objetoDoRequerimento') === 'outro' ? '#ccc' : '#cbd5e1' }}; color: {{ old('objetoDoRequerimento') === 'outro' ? '#111827' : '#64748b' }}; cursor: {{ old('objetoDoRequerimento') === 'outro' ? 'text' : 'not-allowed' }};"
                   placeholder="Especifique caso necessário"
                   oninput="const el = document.getElementById('nome-assunto-outro-preview'); if(el) el.textContent = 'Outro: ' + this.value;">
        </div>
    </fieldset>

    <!-- 3. Justificativa / Motivo -->
    <fieldset>
        <legend>Justificativa / Motivo</legend>
        <div class="campo">
            <textarea name="motivo" rows="4" placeholder="Descreva os motivos da sua solicitação...">{{ old('motivo') }}</textarea>
        </div>
    </fieldset>

    <button type="button" class="btn-enviar" onclick="mudarPasso(2)" style="margin-top: 10px;">Próximo</button>
</div>
