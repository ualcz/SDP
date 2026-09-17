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
    <table id="{{ $tabelaDocsId }}" class="tabela-docs-nested" style="margin-top: 4px; {{ $hasDocs ? '' : 'display: none;' }}">
        <thead>
            <tr>
                <th>Documento *</th>
                <th>Orientações</th>
                <th style="width: 80px; text-align: center;">Obrigatório</th>
                <th style="width: 50px; text-align: center;">Ações</th>
            </tr>
        </thead>
        <tbody id="{{ $tbodyDocsId }}">
            {{-- Documentos existentes já salvos no banco --}}
            @foreach($assunto->documentos as $doc)
                @php($formDocDeleteId = 'form-doc-delete-' . $doc->id)

                {{-- Form de exclusão do documento individual se necessário --}}
                <form id="{{ $formDocDeleteId }}" action="{{ route('admin.documentos.destroy', $doc->id) }}" method="POST"
                      onsubmit="return confirm('Deseja excluir este anexo?');">
                    @csrf
                    @method('DELETE')
                </form>

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
                    <td style="text-align: center;">
                        <input @if(isset($formUpdateId)) form="{{ $formUpdateId }}" @endif
                               type="checkbox"
                               name="documentos[{{ $doc->id }}][obrigatorio]"
                               value="1"
                               {{ $doc->obrigatorio ? 'checked' : '' }}
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

    {{-- Estado vazio exibido quando não há anexos --}}
    <div id="{{ $emptyDocsId }}" style="background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 6px; padding: 10px 14px; margin-top: 6px; display: {{ $hasDocs ? 'none' : 'flex' }}; justify-content: space-between; align-items: center;">
        <span style="font-size: 0.8125rem; color: #64748b;">Nenhum anexo cadastrado ainda.</span>
        <button type="button" class="btn-destaque-anexo" onclick="adicionarLinhaNovoAnexoEdicao('{{ $assunto->id }}', '{{ $formUpdateId }}')" style="padding: 4px 10px; font-size: 0.75rem;">
            + Adicionar Anexo
        </button>
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
        <td style="text-align: center;">
            <input form="${formUpdateId}"
                   type="checkbox"
                   name="novos_documentos[${idx}][obrigatorio]"
                   value="1"
                   checked
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
