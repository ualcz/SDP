{{-- SEÇÃO DE DOCUMENTOS DENTRO DO PAINEL DE EDIÇÃO DO ASSUNTO --}}
@php($hasDocs = $assunto->documentos->isNotEmpty())
@php($tbodyDocsId = 'tbody-docs-' . $assunto->id)
@php($tabelaDocsId = 'tabela-docs-' . $assunto->id)
@php($emptyDocsId = 'empty-docs-' . $assunto->id)

<div class="docs-secao">

    {{-- Cabeçalho da Seção com Botão de Adicionar Anexo --}}
    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 6px; margin-bottom: 10px;">
        <span style="font-size: 0.8125rem; font-weight: 700; color: #334155;">
            Documentos e Anexos Exigidos ({{ $assunto->documentos->count() }})
        </span>
        <button type="button"
                class="btn-destaque-anexo"
                onclick="adicionarLinhaNovoAnexoEdicao('{{ $assunto->id }}', '{{ $formUpdateId }}')"
                style="padding: 5px 14px; font-size: 0.8rem;">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Adicionar Anexo
        </button>
    </div>

    {{-- Tabela com documentos existentes e novos dinâmicos --}}
    <div style="overflow-x: auto;">
    <table id="{{ $tabelaDocsId }}" class="tabela-docs-nested" style="min-width: 820px; margin-top: 4px; {{ $hasDocs ? '' : 'display: none;' }}">
        <thead>
            <tr>
                <th>Documento *</th>
                <th>Orientações</th>
                <th>Modelo DOCX preenchível</th>
                <th style="width: 80px; text-align: center;">Obrigatório</th>
                <th style="width: 170px; text-align: center;" title="Para menores, exigir uma cópia deste documento de um responsável legal">Pedir cópia de responsável</th>
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
                    <td>
                        <input @if(isset($formUpdateId)) form="{{ $formUpdateId }}" @endif
                               type="text"
                               name="documentos[{{ $doc->id }}][nome]"
                               value="{{ $doc->nome }}"
                               required
                               class="input-tabela"
                               style="font-size: 0.8125rem;">
                    </td>
                    <td>
                        <input @if(isset($formUpdateId)) form="{{ $formUpdateId }}" @endif
                               type="text"
                               name="documentos[{{ $doc->id }}][descricao]"
                               value="{{ $doc->descricao }}"
                               class="input-tabela"
                               style="font-size: 0.8125rem;"
                               placeholder="Orientações opcionais...">
                    </td>
                    <td>
                        @if($doc->arquivo_modelo)
                            <div style="display: flex; align-items: center; gap: 6px; margin-bottom: 5px; flex-wrap: wrap;">
                                <a href="{{ route('admin.documentos.baixar-modelo', $doc->id) }}"
                                   target="_blank"
                                   style="display: inline-flex; align-items: center; gap: 4px; padding: 3px 8px; font-size: 0.75rem; background: #e0f2fe; color: #0369a1; border-radius: 4px; text-decoration: none; font-weight: 600;"
                                   title="Baixar arquivo DOCX do modelo atual">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                        <polyline points="7 10 12 15 17 10"></polyline>
                                        <line x1="12" y1="15" x2="12" y2="3"></line>
                                    </svg>
                                    Baixar DOCX
                                </a>
                                <button form="{{ $formDocRemoverModeloId }}"
                                        type="submit"
                                        style="background: none; border: none; color: #ef4444; font-size: 0.72rem; cursor: pointer; padding: 2px 4px;"
                                        title="Remover modelo DOCX cadastrado">
                                    ✕ Remover
                                </button>
                            </div>
                        @endif
                        <input @if(isset($formUpdateId)) form="{{ $formUpdateId }}" @endif
                               type="file"
                               name="documentos[{{ $doc->id }}][arquivo_modelo]"
                               accept=".docx,application/vnd.openxmlformats-officedocument.wordprocessingml.document"
                               class="input-tabela"
                               style="font-size: 0.75rem;">
                        <small style="display:block; font-size:0.68rem; color:#64748b; margin-top:2px;">
                            {{ $doc->arquivo_modelo ? 'Envie outro se desejar substituir' : 'Selecione um arquivo .docx' }}
                        </small>
                    </td>
                    <td style="text-align: center;">
                        <input @if(isset($formUpdateId)) form="{{ $formUpdateId }}" @endif
                               type="checkbox"
                               name="documentos[{{ $doc->id }}][obrigatorio]"
                               value="1"
                               {{ $doc->obrigatorio ? 'checked' : '' }}
                               style="width: 15px; height: 15px; accent-color: #2563eb; cursor: pointer;">
                    </td>
                    <td style="text-align: center;">
                        <input @if(isset($formUpdateId)) form="{{ $formUpdateId }}" @endif
                               type="checkbox"
                               name="documentos[{{ $doc->id }}][exige_documentos_responsaveis]"
                               value="1"
                               {{ $doc->exige_documentos_responsaveis ? 'checked' : '' }}
                               title="Para menores, exigir uma cópia de um responsável legal"
                               style="width: 15px; height: 15px; accent-color: #2563eb; cursor: pointer;">
                    </td>
                    <td style="text-align: center;">
                        <button form="{{ $formDocDeleteId }}"
                                type="submit"
                                class="btn-delete-doc-sm"
                                title="Excluir este anexo">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
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
    <div id="{{ $emptyDocsId }}" style="background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 6px; padding: 10px 14px; margin-top: 6px; display: {{ $hasDocs ? 'none' : 'flex' }}; justify-content: space-between; align-items: center;">
        <span style="font-size: 0.8125rem; color: #64748b;">Nenhum anexo cadastrado ainda.</span>
    </div>
    <small style="display:block; margin-top:8px; color:#64748b;">
        No DOCX, use campos como <code>&#123;&#123; nome &#125;&#125;</code>, <code>&#123;&#123; cpf &#125;&#125;</code>,
        <code>&#123;&#123; matricula &#125;&#125;</code>, <code>&#123;&#123; endereco &#125;&#125;</code> e <code>&#123;&#123; data &#125;&#125;</code>.
    </small>
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
        <td>
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
        <td>
            <input form="${formUpdateId}"
                   type="text"
                   name="novos_documentos[${idx}][descricao]"
                   class="input-tabela"
                   placeholder="Orientações opcionais..."
                   style="font-size: 0.8125rem;">
        </td>
        <td>
            <input form="${formUpdateId}"
                   type="file"
                   name="novos_documentos[${idx}][arquivo_modelo]"
                   accept=".docx,application/vnd.openxmlformats-officedocument.wordprocessingml.document"
                   class="input-tabela"
                   style="font-size: 0.75rem;">
        </td>
        <td style="text-align: center;">
            <input form="${formUpdateId}"
                   type="checkbox"
                   name="novos_documentos[${idx}][obrigatorio]"
                   value="1"
                   checked
                   style="width: 15px; height: 15px; accent-color: #2563eb; cursor: pointer;">
        </td>
        <td style="text-align: center;">
            <input form="${formUpdateId}"
                   type="checkbox"
                   name="novos_documentos[${idx}][exige_documentos_responsaveis]"
                   value="1"
                   title="Para menores, exigir uma cópia de um responsável legal"
                   style="width: 15px; height: 15px; accent-color: #2563eb; cursor: pointer;">
        </td>
        <td style="text-align: center;">
            <button type="button"
                    class="btn-delete-doc-sm"
                    title="Remover este anexo"
                    onclick="removerLinhaDocEdicao(this, '${assuntoId}')">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
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
