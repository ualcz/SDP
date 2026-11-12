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
            {{-- <label class="opcao-objeto-card {{ old('objetoDoRequerimento') === 'outro' ? 'opcao-selecionada' : '' }}">
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
        </div> --}}
    </fieldset>

    <!-- 3. Justificativa / Motivo -->
    <fieldset>
        <legend>Justificativa / Motivo <span style="color: #dc2626; font-weight: bold;" title="Campo Obrigatório">*</span></legend>
        <div class="campo">
            <textarea name="motivo" id="campoMotivo" rows="4" placeholder="Descreva os motivos da sua solicitação..." required>{{ old('motivo') }}</textarea>
            <small style="color: #dc2626; display: none; margin-top: 4px; font-weight: 500;" id="erro-motivo">Por favor, preencha a Justificativa / Motivo antes de prosseguir.</small>
        </div>
    </fieldset>
