<!-- Etapa 3: Documentos Obrigatórios -->
<div class="form-step" data-step="3">

    <style>
        .custom-file-upload {
            display: block;
            padding: 16px;
            background: #f9fafb;
            border: 2px dashed #d1d5db;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: border-color 0.2s, background-color 0.2s;
        }

        .custom-file-upload:hover {
            background-color: #f3f4f6;
            border-color: #9ca3af;
        }

        .custom-file-upload input[type="file"] {
            display: none !important;
        }

        .file-upload-text {
            font-size: 13px;
            color: #4b5563;
        }

        .file-feedback-box {
            display: none;
            margin-top: 8px;
            padding: 8px 12px;
            background: #f3f4f6;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            font-size: 12px;
            color: #374151;
        }

        .file-feedback-box ul {
            margin: 4px 0 0;
            padding-left: 18px;
        }

        .bloco-documentos-assunto .documento-configurado {
            background: #f8fafc;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #dbe3ed;
            margin-bottom: 16px;
        }

        .bloco-documentos-assunto .documento-configurado.documento-obrigatorio {
            background: #f9ebeb;
            border-color: #ffc3c3;
        }

        .bloco-documentos-assunto .documento-configurado > label {
            color: #1e293b;
        }
    </style>

    <div style="text-align: center; margin-bottom: 40px; font-size: 1.2rem;">
        <h3 id="titulo-setor-etapa3" style="margin: 0;">{{ $modeloAtivo['titulo'] ?? 'Requerimento Geral' }}</h3>
        <small id="nome-setor-etapa3" style="color: #666;">{{ $setorDestino['nome'] ?? 'Setor Responsável' }}</small>
    </div>

    <!-- Documentos e Anexos -->
    <fieldset class="secao-anexos">
        <legend>Documentos e Anexos</legend>

        @php
            $assuntosList = array_values($modeloAtivo['assuntos_detalhes'] ?? []);
            $assuntoSelecionadoOld = old('objetoDoRequerimento');
        @endphp

        <div id="container-documentos">
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
                        <h3 style="font-size: 1.25rem; text-align: center; margin-bottom: 12px;">Documentos Obrigatórios</h3>
                        @foreach($docs as $doc)
                            @php
                                $tiposAceitos = !empty($doc['tipos_aceitos'])
                                    ? '.' . str_replace(',', ',.', str_replace([' ', '.'], ['', ''], strtolower($doc['tipos_aceitos'])))
                                    : '.pdf,.doc,.docx,.png,.jpg,.jpeg';
                            @endphp
                            <div @class([
                                'campo',
                                'documento-configurado',
                                'documento-obrigatorio' => $doc['obrigatorio'],
                            ])>
                                <label style="display: block; font-weight: bold; margin-bottom: 4px; font-size: 14px;">
                                    {{ $doc['nome'] }}
                                    @if($doc['obrigatorio'])
                                        <span style="color: #dc2626; font-weight: normal;">(Obrigatório)</span>
                                    @else
                                        <span style="color: #6b7280; font-weight: normal;">(Opcional)</span>
                                    @endif
                                </label>
                                @if(!empty(trim($doc['descricao'] ?? '')))
                                    <div style="display: block; background: #fffbeb; color: #78350f; padding: 10px 14px; margin: 8px 0 12px; font-size: 15px; font-weight: 600; line-height: 1.5; border: 1px solid #fcd34d;">{{ $doc['descricao'] }}</div>
                                @endif
                                @if(!empty($doc['arquivo_modelo']) || !empty($doc['link_modelo']))
                                    <div style="display: flex; flex-wrap: wrap; gap: 14px; margin: 4px 0 12px;">
                                        @if(!empty($doc['arquivo_modelo']))
                                            <a href="{{ route('documentos-assunto.modelo', $doc['id']) }}"
                                               style="display: inline-flex; align-items: center; gap: 6px; color: #1d4ed8; font-size: 13px; line-height: 1.2; font-weight: 600; text-decoration: underline;"
                                               title="Baixar modelo preenchido com seus dados em formato PDF">
                                                <svg aria-hidden="true" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                                    <polyline points="7 10 12 15 17 10"></polyline>
                                                    <line x1="12" y1="15" x2="12" y2="3"></line>
                                                </svg>
                                                Baixar modelo preenchido (PDF)
                                            </a>
                                        @endif
                                        @if(!empty($doc['link_modelo']))
                                            <a href="{{ $doc['link_modelo'] }}" target="_blank" rel="noopener noreferrer"
                                               style="display: inline-flex; align-items: center; gap: 6px; color: #0284c7; font-size: 13px; line-height: 1.2; font-weight: 600; text-decoration: underline;"
                                               title="Acessar site ou formulário externo">
                                                <svg aria-hidden="true" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                                    <polyline points="15 3 21 3 21 9"></polyline>
                                                    <line x1="10" y1="14" x2="21" y2="3"></line>
                                                </svg>
                                                Acessar site / formulário externo
                                            </a>
                                        @endif
                                    </div>
                                @endif

                                <x-file-input
                                    name="documentos[{{ $doc['id'] ?? $loop->index }}]"
                                    required="{{ $doc['obrigatorio'] ? 'true' : 'false' }}"
                                    label="{{ $doc['nome'] }}"
                                    accept="{{ $tiposAceitos }}"
                                    :multiple="false"
                                />
                                @if($alunoMenor && !empty($doc['exige_documentos_responsaveis']))
                                    <x-file-input
                                        name="documentos_responsavel[{{ $doc['id'] }}]"
                                        label="{{ $doc['nome'] }} de um responsável legal"
                                        helpText="Anexe a cópia do documento de um responsável."
                                        required="true"
                                        accept="{{ $tiposAceitos }}"
                                        :multiple="false"
                                    />
                                @endif
                            </div>
                        @endforeach
                    @else
                        <p style="color: #666; font-size: 14px; margin-bottom: 12px;">
                            Este assunto não possui documentos obrigatórios.
                        </p>
                    @endif
                </div>
            @endforeach
        </div>{{-- #container-documentos --}}

    </fieldset>

    <div class="step-nav">
        <button type="button" class="btn-voltar" onclick="mudarPasso(2)">Anterior</button>
        <button type="submit" class="btn-enviar" id="btn-enviar-requerimento" disabled
                title="Anexe todos os documentos obrigatórios para enviar">
            Enviar Requerimento
        </button>
    </div>
</div>
