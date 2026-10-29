{{-- SEÇÃO DE DOCUMENTOS DENTRO DO PAINEL DE EDIÇÃO DO ASSUNTO --}}
@php($hasDocs = $assunto->documentos->isNotEmpty())
@php($tbodyDocsId = 'tbody-docs-' . $assunto->id)
@php($tabelaDocsId = 'tabela-docs-' . $assunto->id)
@php($emptyDocsId = 'empty-docs-' . $assunto->id)

<div class="docs-secao">

    {{-- Cabeçalho da Seção com Botão de Adicionar Anexo --}}
    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 6px; margin-bottom: 12px;">
        <div style="display: flex; align-items: center; gap: 8px;">
            <span style="font-size: 0.875rem; font-weight: 700; color: #1e293b;">
                Documentos e Anexos Exigidos
            </span>
            <span class="badge-docs {{ $hasDocs ? 'has-docs' : '' }}" style="font-size: 0.72rem;">
                {{ $assunto->documentos->count() }} {{ $assunto->documentos->count() === 1 ? 'item' : 'itens' }}
            </span>
        </div>
        <button type="button"
                class="btn-destaque-anexo"
                onclick="adicionarLinhaNovoAnexoEdicao('{{ $assunto->id }}', '{{ $formUpdateId }}')"
                style="padding: 6px 14px; font-size: 0.8rem;">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Adicionar Anexo
        </button>
    </div>

    {{-- Tabela com documentos existentes e novos dinâmicos --}}
    <div style="overflow-x: auto;">
    <table id="{{ $tabelaDocsId }}" class="tabela-docs-nested" style="min-width: 960px; {{ $hasDocs ? '' : 'display: none;' }}">
        <thead>
            <tr>
                <th style="width: 20%;">Nome do Documento *</th>
                <th style="width: 18%;">Orientações ao Aluno</th>
                <th style="width: 18%;">Link Externo / Site</th>
                <th style="width: 28%;">Modelo DOCX (Preenchível)</th>
                <th style="width: 70px; text-align: center;">Obrigatório</th>
                <th style="width: 120px; text-align: center;" title="Exigir documento do responsável legal se o aluno for menor de idade">Cópia Responsável</th>
                <th style="width: 50px; text-align: center;">Ações</th>
            </tr>
        </thead>
        <tbody id="{{ $tbodyDocsId }}">
            {{-- Documentos existentes já salvos no banco --}}
            @foreach($assunto->documentos as $doc)
                @php($formDocDeleteId = 'form-doc-delete-' . $doc->id)
                @php($formDocRemoverModeloId = 'form-doc-remover-modelo-' . $doc->id)

                {{-- Form de exclusão do documento individual se necessário --}}
                <form id="{{ $formDocDeleteId }}" action="{{ route('admin.documentos.destroy', $doc->id) }}" method="POST"
                      onsubmit="return confirm('Deseja excluir este anexo?');">
                    @csrf
                    @method('DELETE')
                </form>

                @if($doc->arquivo_modelo)
                    <form id="{{ $formDocRemoverModeloId }}" action="{{ route('admin.documentos.remover-modelo', $doc->id) }}" method="POST"
                          onsubmit="return confirm('Deseja remover o arquivo modelo DOCX deste anexo?');">
                        @csrf
                        @method('DELETE')
                    </form>
                @endif

                <tr>
                    <td style="vertical-align: middle;">
                        <input @if(isset($formUpdateId)) form="{{ $formUpdateId }}" @endif
                               type="text"
                               name="documentos[{{ $doc->id }}][nome]"
                               value="{{ $doc->nome }}"
                               required
                               class="input-tabela"
                               placeholder="Ex: Termo de Responsabilidade"
                               style="font-size: 0.8125rem;">
                    </td>
                    <td style="vertical-align: middle;">
                        <input @if(isset($formUpdateId)) form="{{ $formUpdateId }}" @endif
                               type="text"
                               name="documentos[{{ $doc->id }}][descricao]"
                               value="{{ $doc->descricao }}"
                               class="input-tabela"
                               style="font-size: 0.8125rem;"
                               placeholder="Orientações opcionais...">
                    </td>
                    <td style="vertical-align: middle;">
                        <input @if(isset($formUpdateId)) form="{{ $formUpdateId }}" @endif
                               type="url"
                               name="documentos[{{ $doc->id }}][link_modelo]"
                               value="{{ $doc->link_modelo }}"
                               class="input-tabela"
                               style="font-size: 0.8125rem;"
                               placeholder="https://exemplo.com/site">
                    </td>
                    <td style="vertical-align: middle;">
                        @if($doc->arquivo_modelo)
                            <div class="doc-modelo-card">
                                <div class="doc-modelo-header">
                                    <div class="doc-modelo-actions">
                                        {{-- Visualizar modelo pré-preenchido em PDF --}}
                                        <a href="{{ route('admin.documentos.visualizar-modelo', $doc->id) }}"
                                           target="_blank"
                                           class="btn-doc-view"
                                           title="Visualizar modelo preenchido em PDF em nova aba">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                <circle cx="12" cy="12" r="3"></circle>
                                            </svg>
                                            Visualizar
                                        </a>

                                        {{-- Baixar arquivo DOCX original --}}
                                        <a href="{{ route('admin.documentos.baixar-modelo', $doc->id) }}"
                                           class="btn-doc-download"
                                           title="Baixar arquivo DOCX do modelo atual">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                                <polyline points="7 10 12 15 17 10"></polyline>
                                                <line x1="12" y1="15" x2="12" y2="3"></line>
                                            </svg>
                                            Baixar DOCX
                                        </a>

                                        {{-- Remover modelo --}}
                                        <button form="{{ $formDocRemoverModeloId }}"
                                                type="submit"
                                                class="btn-doc-remove"
                                                title="Remover modelo DOCX cadastrado">
                                            ✕ Remover
                                        </button>
                                    </div>
                                </div>
                                <div class="doc-modelo-upload">
                                    <span class="label-substituir-docx">Substituir por outro .docx:</span>
                                    <input @if(isset($formUpdateId)) form="{{ $formUpdateId }}" @endif
                                           type="file"
                                           name="documentos[{{ $doc->id }}][arquivo_modelo]"
                                           accept=".docx,application/vnd.openxmlformats-officedocument.wordprocessingml.document"
                                           class="input-file-clean">
                                </div>
                            </div>
                        @else
                            <div class="doc-modelo-upload">
                                <input @if(isset($formUpdateId)) form="{{ $formUpdateId }}" @endif
                                       type="file"
                                       name="documentos[{{ $doc->id }}][arquivo_modelo]"
                                       accept=".docx,application/vnd.openxmlformats-officedocument.wordprocessingml.document"
                                       class="input-file-clean">
                                <span style="font-size: 0.68rem; color: #64748b; margin-top: 2px;">
                                    Opcional: anexe um modelo <strong>.docx</strong> preenchível
                                </span>
                            </div>
                        @endif
                    </td>
                    <td style="text-align: center; vertical-align: middle;">
                        <input @if(isset($formUpdateId)) form="{{ $formUpdateId }}" @endif
                               type="checkbox"
                               name="documentos[{{ $doc->id }}][obrigatorio]"
                               value="1"
                               {{ $doc->obrigatorio ? 'checked' : '' }}
                               title="Marcar como documento obrigatório"
                               style="width: 17px; height: 17px; accent-color: #2563eb; cursor: pointer;">
                    </td>
                    <td style="text-align: center; vertical-align: middle;">
                        <input @if(isset($formUpdateId)) form="{{ $formUpdateId }}" @endif
                               type="checkbox"
                               name="documentos[{{ $doc->id }}][exige_documentos_responsaveis]"
                               value="1"
                               {{ $doc->exige_documentos_responsaveis ? 'checked' : '' }}
                               title="Para alunos menores de idade, exigir documento de um responsável legal"
                               style="width: 17px; height: 17px; accent-color: #2563eb; cursor: pointer;">
                    </td>
                    <td style="text-align: center; vertical-align: middle;">
                        <button form="{{ $formDocDeleteId }}"
                                type="submit"
                                class="btn-delete-doc-sm"
                                title="Excluir este anexo">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="3 6 5 6 21 6"></polyline>
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                            </svg>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    </div>

    {{-- Estado vazio exibido quando não há anexos --}}
    <div id="{{ $emptyDocsId }}" style="background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 6px; padding: 12px 16px; margin-top: 6px; display: {{ $hasDocs ? 'none' : 'flex' }}; justify-content: space-between; align-items: center;">
        <span style="font-size: 0.8125rem; color: #64748b;">Nenhum anexo exigido cadastrado para este requerimento.</span>
    </div>

    {{-- Dica visual sobre as tags aceitas no modelo DOCX --}}
    <div class="dica-tags-docx">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2" style="flex-shrink: 0; margin-top: 1px;">
            <circle cx="12" cy="12" r="10"></circle>
            <line x1="12" y1="16" x2="12" y2="12"></line>
            <line x1="12" y1="8" x2="12.01" y2="8"></line>
        </svg>
        <div>
            <strong>Modelos e Links:</strong> Você pode informar um <strong>Link Externo</strong> (site/sistema/formulário) e/ou anexar um <strong>Modelo DOCX</strong>. No arquivo DOCX, use tags como <code>&#123;&#123; nome &#125;&#125;</code>, <code>&#123;&#123; cpf &#125;&#125;</code>, <code>&#123;&#123; matricula &#125;&#125;</code>, <code>&#123;&#123; turma_codigo &#125;&#125;</code>, <code>&#123;&#123; email &#125;&#125;</code>, <code>&#123;&#123; telefone &#125;&#125;</code>, <code>&#123;&#123; data_nascimento &#125;&#125;</code>, <code>&#123;&#123; endereco &#125;&#125;</code> e <code>&#123;&#123; data &#125;&#125;</code> para preenchimento automático.
        </div>
    </div>
</div>

<script>
if (typeof window.contadorNovosDocsEdicao === 'undefined') {
    window.contadorNovosDocsEdicao = 0;
}

function adicionarLinhaNovoAnexoEdicao(assuntoId, formUpdateId) {
    const tbody = document.getElementById('tbody-docs-' + assuntoId);
    const tabela = document.getElementById('tabela-docs-' + assuntoId);
    const emptyBox = document.getElementById('empty-docs-' + assuntoId);

    if (!tbody) return;

    const idx = window.contadorNovosDocsEdicao++;
    const tr = document.createElement('tr');
    tr.className = 'tr-novo-doc-edicao';
    tr.innerHTML = `
        <td style="vertical-align: middle;">
            <div style="display: flex; align-items: center; gap: 6px;">
                <input form="${formUpdateId}"
                       type="text"
                       name="novos_documentos[${idx}][nome]"
                       required
                       class="input-tabela"
                       placeholder="Ex: Histórico Escolar"
                       style="font-size: 0.8125rem;">
                <span style="font-size: 0.65rem; background: #dcfce7; color: #15803d; font-weight: 700; padding: 2px 5px; border-radius: 4px; white-space: nowrap;">Novo</span>
            </div>
        </td>
        <td style="vertical-align: middle;">
            <input form="${formUpdateId}"
                   type="text"
                   name="novos_documentos[${idx}][descricao]"
                   class="input-tabela"
                   placeholder="Orientações opcionais..."
                   style="font-size: 0.8125rem;">
        </td>
        <td style="vertical-align: middle;">
            <input form="${formUpdateId}"
                   type="url"
                   name="novos_documentos[${idx}][link_modelo]"
                   class="input-tabela"
                   placeholder="https://exemplo.com/site"
                   style="font-size: 0.8125rem;">
        </td>
        <td style="vertical-align: middle;">
            <div class="doc-modelo-upload">
                <input form="${formUpdateId}"
                       type="file"
                       name="novos_documentos[${idx}][arquivo_modelo]"
                       accept=".docx,application/vnd.openxmlformats-officedocument.wordprocessingml.document"
                       class="input-file-clean">
                <span style="font-size: 0.68rem; color: #64748b; margin-top: 2px;">
                    Opcional: modelo <strong>.docx</strong>
                </span>
            </div>
        </td>
        <td style="text-align: center; vertical-align: middle;">
            <input form="${formUpdateId}"
                   type="checkbox"
                   name="novos_documentos[${idx}][obrigatorio]"
                   value="1"
                   checked
                   title="Marcar como obrigatório"
                   style="width: 17px; height: 17px; accent-color: #2563eb; cursor: pointer;">
        </td>
        <td style="text-align: center; vertical-align: middle;">
            <input form="${formUpdateId}"
                   type="checkbox"
                   name="novos_documentos[${idx}][exige_documentos_responsaveis]"
                   value="1"
                   title="Para alunos menores de idade, exigir documento de um responsável legal"
                   style="width: 17px; height: 17px; accent-color: #2563eb; cursor: pointer;">
        </td>
        <td style="text-align: center; vertical-align: middle;">
            <button type="button"
                    class="btn-delete-doc-sm"
                    title="Remover este anexo"
                    onclick="removerLinhaDocEdicao(this, '${assuntoId}')">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="3 6 5 6 21 6"></polyline>
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                </svg>
            </button>
        </td>
    `;
    tbody.appendChild(tr);

    if (tabela) tabela.style.display = 'table';
    if (emptyBox) emptyBox.style.display = 'none';

    const input = tr.querySelector('input[type="text"]');
    if (input) setTimeout(() => input.focus(), 50);
}

function removerLinhaDocEdicao(btn, assuntoId) {
    const tr = btn.closest('tr');
    if (tr) tr.remove();

    const tbody = document.getElementById('tbody-docs-' + assuntoId);
    const tabela = document.getElementById('tabela-docs-' + assuntoId);
    const emptyBox = document.getElementById('empty-docs-' + assuntoId);

    if (tbody && !tbody.querySelector('tr')) {
        if (tabela) tabela.style.display = 'none';
        if (emptyBox) emptyBox.style.display = 'flex';
    }
}
</script>
