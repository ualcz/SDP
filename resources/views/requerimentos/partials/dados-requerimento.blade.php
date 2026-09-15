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
                    $isChecked = old('objetoDoRequerimento') ? (old('objetoDoRequerimento') === $descricao) : $loop->first;
                @endphp
                <label style="display: flex; flex-direction: column; align-items: flex-start; margin-bottom: 8px; cursor: pointer;">
                    <span style="display: inline-flex; align-items: center; gap: 6px;">
                        <input type="radio" 
                               name="objetoDoRequerimento" 
                               value="{{ $descricao }}" 
                               onchange="mostrarDocumentosAssunto({{ $indexAssunto }})" 
                               {{ $isChecked ? 'checked' : '' }}>
                        {{ $descricao }}
                    </span>
                    @if(!empty($obs))
                        <small style="color: #e6904f; margin-left: 22px; font-size: 0.78rem;">{{ $obs }}</small>
                    @endif
                </label>
            @endforeach
        </div>

        <div class="campo" style="margin-top: 10px;">
            <label>Outro / Detalhe adicional (opcional):</label>
            <input type="text" 
                   name="objeto_outro" 
                   placeholder="Especifique caso necessário"
                   oninput="if(this.value.trim() !== '') { mostrarDocumentosAssunto('outro'); const el = document.getElementById('nome-assunto-outro-preview'); if(el) el.textContent = 'Outro: ' + this.value; }">
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
