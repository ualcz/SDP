{{-- 2. REQUERIMENTOS DO MODELO COM OBSERVAÇÃO E DOCUMENTOS --}}
<div class="secao-bloco">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
        <h3 style="margin: 0; font-size: 1.15rem; font-weight: 600; color: #1f2937;">
            2. Requerimentos e Documentos Exigidos
        </h3>
        <a href="{{ route('admin.setores.assuntos.create', $modelo->id) }}"
           class="btn-salvar"
           style="text-decoration: none; display: inline-flex; align-items: center; gap: 6px; padding: 7px 16px; font-weight: 600; font-size: 0.8125rem; border-radius: 6px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
             Novo Requerimento
        </a>
    </div>

    <div style="overflow-x: auto;">
        <table class="tabela-assuntos">
            <thead>
                <tr>
                    <th class="col-descricao">Descrição do Requerimento</th>
                    <th class="col-observacao">Observação / Requisito</th>
                    <th class="col-docs">Documentos</th>
                    <th class="col-ordem">Ordem</th>
                    <th class="col-ativo">Ativo</th>
                    <th class="col-acoes">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($modelo->assuntos as $assunto)
                    @php
                        $panelId      = 'painel-assunto-' . $assunto->id;
                        $formUpdateId = 'form-assunto-update-' . $assunto->id;
                        $docsCount    = $assunto->documentos->count();
                    @endphp

                    {{-- Formulário de update (fora do <tr> para ser válido no HTML) --}}
                    <form id="{{ $formUpdateId }}"
                          action="{{ route('admin.assuntos.update', $assunto->id) }}"
                          method="POST">
                        @csrf
                        @method('PUT')
                    </form>

                    {{-- Linha principal (somente leitura) --}}
                    <tr class="tr-assunto-linha">
                        <td title="{{ $assunto->descricao }}">{{ $assunto->descricao }}</td>
                        <td title="{{ $assunto->observacao }}" style="color: #6b7280;">
                            {{ $assunto->observacao ?: '—' }}
                        </td>
                        <td style="text-align: center;">
                            <span class="badge-docs {{ $docsCount > 0 ? 'has-docs' : '' }}">
                                {{ $docsCount }}  Anexos
                            </span>
                        </td>
                        <td style="text-align: center; color: #6b7280;">{{ $assunto->ordem }}</td>
                        <td style="text-align: center;">
                            @if($assunto->ativo)
                                <span style="color: #059669; font-weight: 600; font-size: 0.75rem;">Sim</span>
                            @else
                                <span style="color: #dc2626; font-weight: 600; font-size: 0.75rem;">Não</span>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            <button type="button"
                                    class="btn-editar-sm"
                                    onclick="togglePainelAssunto('{{ $panelId }}')"
                                    title="Editar requerimento e gerenciar documentos">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                </svg>
                                Editar
                            </button>
                        </td>
                    </tr>

                    {{-- Painel expansível: edição + documentos --}}
                    <tr id="{{ $panelId }}" class="tr-painel-assunto" style="display: none;">
                        <td colspan="6" style="padding: 0;">
                            <div class="painel-edicao-assunto">
                               
                                {{-- Campos de edição --}}
                                <div class="painel-campos">
                                    <div style="flex: 3; min-width: 220px;">
                                        <label class="label-campo">Descrição *</label>
                                        <input form="{{ $formUpdateId }}"
                                               type="text"
                                               name="descricao"
                                               value="{{ $assunto->descricao }}"
                                               required
                                               class="input-tabela">
                                    </div>
                                    <div style="flex: 4; min-width: 220px;">
                                        <label class="label-campo">Observação / Requisito</label>
                                        <input form="{{ $formUpdateId }}"
                                               type="text"
                                               name="observacao"
                                               value="{{ $assunto->observacao }}"
                                               placeholder="Ex: Necessita assinatura do coordenador"
                                               class="input-tabela">
                                    </div>
                                    <div style="width: 80px;">
                                        <label class="label-campo">Ordem</label>
                                        <input form="{{ $formUpdateId }}"
                                               type="number"
                                               name="ordem"
                                               value="{{ $assunto->ordem }}"
                                               class="input-tabela"
                                               style="text-align: center;">
                                    </div>
                                    <div style="display: flex; flex-direction: column; align-items: center; justify-content: flex-end; gap: 4px;">
                                        <label class="label-campo">Ativo</label>
                                        <input form="{{ $formUpdateId }}"
                                               type="checkbox"
                                               name="ativo"
                                               value="1"
                                               {{ $assunto->ativo ? 'checked' : '' }}
                                               style="width: 16px; height: 16px; accent-color: #2563eb; cursor: pointer;">
                                    </div>
                                </div>

                                {{-- Seção de documentos --}}
                                @include('admin.setor.partials.item-assunto-documentos', [
                                    'assunto'      => $assunto,
                                    'docsRowId'    => null,
                                    'docsCount'    => $docsCount,
                                    'formUpdateId' => $formUpdateId,
                                ])

                                {{-- Rodapé com Ações Estratégicas de Salvar --}}
                                <div style="display: flex; justify-content: flex-end; align-items: center; gap: 10px; margin-top: 14px; padding-top: 12px; border-top: 1px solid #e2e8f0;">
                                    <button type="button"
                                            onclick="togglePainelAssunto('{{ $panelId }}')"
                                            class="btn-voltar"
                                            style="padding: 6px 14px; font-size: 0.8125rem;">
                                        Cancelar
                                    </button>
                                    <button form="{{ $formUpdateId }}"
                                            type="submit"
                                            class="btn-salvar"
                                            style="padding: 7px 20px; font-size: 0.8125rem;">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                            <polyline points="20 6 9 17 4 12"></polyline>
                                        </svg>
                                        Salvar Alterações
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 20px; color: #6b7280;">Nenhum requerimento cadastrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>


<script>
function togglePainelAssunto(panelId) {
    const row = document.getElementById(panelId);
    if (!row) return;
    const isOpen = row.style.display !== 'none';
    row.style.display = isOpen ? 'none' : 'table-row';
}
</script>
