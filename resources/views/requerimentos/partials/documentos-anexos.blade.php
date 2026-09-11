<!-- Etapa 2: Documentos Obrigatórios e Anexos Complementares -->
<div class="form-step" data-step="2" style="display: none;">
    <!-- Documentos e Anexos -->
    <fieldset class="secao-anexos">
        <legend>Documentos e Anexos</legend>

        @php
            $assuntosList = array_values($modeloAtivo['assuntos_detalhes'] ?? []);
            $assuntoSelecionadoOld = old('objetoDoRequerimento');
        @endphp

        @foreach($assuntosList as $idx => $assuntoItem)
            @php
                $isAssuntoAtivo = $assuntoSelecionadoOld 
                    ? ($assuntoSelecionadoOld === $assuntoItem['descricao']) 
                    : ($idx === 0);
                $docs = $assuntoItem['documentos_obrigatorios'] ?? [];
            @endphp

            <div class="bloco-documentos-assunto" id="bloco-doc-{{ $idx }}" style="{{ $isAssuntoAtivo ? '' : 'display: none;' }}">
                <div style="background: #f0fdf4; border: 1px solid #86efac; border-radius: 6px; padding: 10px 14px; margin-bottom: 16px;">
                    <span style="font-size: 12px; color: #15803d; text-transform: uppercase; font-weight: 600; display: block;">Assunto Selecionado:</span>
                    <strong style="font-size: 16px; color: #047857;">{{ $assuntoItem['descricao'] }}</strong>
                </div>

                @if(count($docs) > 0)
                    @foreach($docs as $doc)
                        @php
                            $tiposAceitos = !empty($doc['tipos_aceitos'])
                                ? '.' . str_replace(',', ',.', str_replace([' ', '.'], ['', ''], strtolower($doc['tipos_aceitos'])))
                                : '.pdf,.doc,.docx,.png,.jpg,.jpeg';
                        @endphp
                        <div class="campo" style="margin-bottom: 16px;">
                            <label style="display: block; font-weight: bold; margin-bottom: 4px; font-size: 14px;">
                                {{ $doc['nome'] }}
                                @if($doc['obrigatorio'])
                                    <span style="color: #dc2626; font-weight: normal;">(Obrigatório)</span>
                                @else
                                    <span style="color: #6b7280; font-weight: normal;">(Opcional)</span>
                                @endif
                            </label>
                            @if(!empty($doc['descricao']))
                                <small style="display: block; color: #666; margin-bottom: 6px; font-size: 12px;">{{ $doc['descricao'] }}</small>
                            @endif
                            <input type="file" 
                                   name="documentos[{{ $doc['id'] ?? $loop->index }}]" 
                                   data-obrigatorio="{{ $doc['obrigatorio'] ? 'true' : 'false' }}"
                                   data-nome="{{ $doc['nome'] }}"
                                   accept="{{ $tiposAceitos }}">
                        </div>
                    @endforeach
                @else
                    <p style="color: #666; font-size: 14px; margin-bottom: 12px;">
                        Este assunto não possui documentos obrigatórios.
                    </p>
                @endif
            </div>
        @endforeach

        <!-- Bloco para a opção "Outro" -->
        <div class="bloco-documentos-assunto" id="bloco-doc-outro" style="display: none;">
            <div style="background: #f0fdf4; border: 1px solid #86efac; border-radius: 6px; padding: 10px 14px; margin-bottom: 16px;">
                <span style="font-size: 12px; color: #15803d; text-transform: uppercase; font-weight: 600; display: block;">Assunto Selecionado:</span>
                <strong id="nome-assunto-outro-preview" style="font-size: 16px; color: #047857;">Outro Requerimento</strong>
            </div>
            <p style="color: #666; font-size: 14px; margin-bottom: 12px;">
                Requerimento personalizado. Caso possua documentos comprobatórios, anexe no campo complementar abaixo.
            </p>
        </div>

        <!-- Anexos complementares para qualquer requerimento -->
        <div class="campo" style="margin-top: 15px; border-top: 1px dashed #ddd; padding-top: 12px;">
            <label style="display: block; font-weight: bold; margin-bottom: 4px; font-size: 14px;">
                Anexos complementares (opcional):
            </label>
            <small style="display: block; color: #666; margin-bottom: 6px; font-size: 12px;">
                Adicione outros arquivos ou comprovantes se desejar.
            </small>
            <input type="file" name="arquivos[]" multiple accept=".pdf,.doc,.docx,.png,.jpg,.jpeg">
        </div>
    </fieldset>

    <div class="step-nav">
        <button type="button" class="btn-voltar" onclick="mudarPasso(1)">Anterior</button>
        <button type="submit" class="btn-enviar" id="btn-enviar-requerimento" disabled
                title="Anexe todos os documentos obrigatórios para enviar">
            Enviar Requerimento
        </button>
    </div>
</div>
