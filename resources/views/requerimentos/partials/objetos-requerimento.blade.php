    <!-- 2. Objeto do Requerimento -->
<div class="form-step" data-step="2">

    <div style="text-align: center; margin-bottom: 40px; font-size: 1.2rem;">
        <h3 id="titulo-setor-etapa2" style="margin: 0;">{{ $modeloAtivo['titulo'] ?? 'Requerimento Geral' }}</h3>
        <small id="nome-setor-etapa2" style="color: #666;">{{ $setorDestino['nome'] ?? 'Setor Responsável' }}</small>
    </div>

    <fieldset>
        <legend>Objeto do Requerimento</legend>
        <div class="opcoes-objeto" id="container-objetos">
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
                               data-assunto-id="{{ $assuntoItem['id'] ?? '' }}"
                               onchange="atualizarAssuntoSelecionado(this); alternarObjetoOutro(false); mostrarDocumentosAssunto({{ $indexAssunto }}); document.querySelectorAll('.opcao-objeto-card').forEach(el => el.classList.remove('opcao-selecionada')); this.closest('.opcao-objeto-card').classList.add('opcao-selecionada');"
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
        </div>
    </fieldset>

    <!-- 3. Justificativa / Motivo -->
    <fieldset>
        <legend>Justificativa / Motivo <span style="color: #dc2626; font-weight: bold;" title="Campo Obrigatório">*</span></legend>
        <div class="campo">
            <textarea name="motivo" id="campoMotivo" rows="4" placeholder="Descreva os motivos da sua solicitação..." required>{{ old('motivo') }}</textarea>
            <small style="color: #dc2626; display: none; margin-top: 4px; font-weight: 500;" id="erro-motivo">Por favor, preencha a Justificativa / Motivo antes de prosseguir.</small>
        </div>
    </fieldset>

    <div class="step-nav">
        <button type="button" class="btn-voltar" onclick="mudarPasso(1)">Anterior</button>
        <button type="button" class="btn-enviar" onclick="mudarPasso(3)">Próximo</button>
    </div>

</div>
