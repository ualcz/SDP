{{-- LINHA EXPANSÍVEL: GERENCIAMENTO DE DOCUMENTOS DO REQUERIMENTO --}}
<tr id="{{ $docsRowId }}" class="tr-documentos" style="display: none; background: #f8fafc;">
    <td colspan="7" style="padding: 10px 16px 14px 16px;">
        <div class="box-docs-nested">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                <h5 style="margin: 0; font-size: 0.875rem; font-weight: 600; color: #1e293b;">
                    Documentos exigidos: <span style="color: #059669;">"{{ $assunto->descricao }}"</span>
                </h5>
                <button type="button" onclick="toggleDocumentos('{{ $docsRowId }}')" class="btn-voltar" style="padding: 2px 8px; font-size: 0.75rem;">
                    Fechar &times;
                </button>
            </div>

            @if($assunto->documentos->isNotEmpty())
                <table class="tabela-docs-nested">
                    <thead>
                        <tr>
                            <th>Documento</th>
                            <th>Orientações</th>
                            <th style="width: 85px; text-align: center;">Obrigatório</th>
                            <th style="width: 110px; text-align: center;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($assunto->documentos as $doc)
                            @php($formDocUpdateId = 'form-doc-update-' . $doc->id)
                            @php($formDocDeleteId = 'form-doc-delete-' . $doc->id)

                            <form id="{{ $formDocUpdateId }}" action="{{ route('admin.documentos.update', $doc->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="tipos_aceitos" value="{{ $doc->tipos_aceitos ?? 'pdf,jpg,jpeg,png' }}">
                            </form>
                            <form id="{{ $formDocDeleteId }}" action="{{ route('admin.documentos.destroy', $doc->id) }}" method="POST" onsubmit="return confirm('Deseja excluir este documento?');">
                                @csrf
                                @method('DELETE')
                            </form>

                            <tr>
                                <td>
                                    <input form="{{ $formDocUpdateId }}" type="text" name="nome" value="{{ $doc->nome }}" required class="input-tabela" style="font-size: 0.8125rem;">
                                </td>
                                <td>
                                    <input form="{{ $formDocUpdateId }}" type="text" name="descricao" value="{{ $doc->descricao }}" class="input-tabela" style="font-size: 0.8125rem;" placeholder="Orientações opcionais...">
                                </td>
                                <td style="text-align: center;">
                                    <input form="{{ $formDocUpdateId }}" type="checkbox" name="obrigatorio" value="1" {{ $doc->obrigatorio ? 'checked' : '' }} title="Obrigatório">
                                </td>
                                <td style="text-align: center;">
                                    <div style="display: inline-flex; gap: 4px; align-items: center;">
                                        <button form="{{ $formDocUpdateId }}" type="submit" class="btn-salvar-sm" style="padding: 4px 8px; font-size: 0.75rem;" title="Salvar">
                                            Salvar
                                        </button>
                                        <button form="{{ $formDocDeleteId }}" type="submit" class="btn-excluir-sm" style="padding: 4px 8px; font-size: 0.75rem;" title="Excluir">
                                            Excluir
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            {{-- Formulário para adicionar documento a este requerimento --}}
            <form action="{{ route('admin.documentos.store', $assunto->id) }}" method="POST" style="margin-top: {{ $assunto->documentos->isNotEmpty() ? '8px' : '4px' }};">
                @csrf
                <div style="display: flex; gap: 8px; flex-wrap: wrap; align-items: flex-end;">
                    <div style="flex: 2; min-width: 170px;">
                        <label style="display: block; font-size: 0.75rem; font-weight: 600; color: #475569; margin-bottom: 2px;">Documento *</label>
                        <input type="text" name="nome" placeholder="Ex: Histórico Escolar" required class="input-tabela" style="font-size: 0.8125rem;">
                    </div>
                    <div style="flex: 3; min-width: 200px;">
                        <label style="display: block; font-size: 0.75rem; font-weight: 600; color: #475569; margin-bottom: 2px;">Orientações (opcional)</label>
                        <input type="text" name="descricao" placeholder="Ex: Emitido pela biblioteca..." class="input-tabela" style="font-size: 0.8125rem;">
                    </div>
                    <div style="display: flex; align-items: center; gap: 4px; padding-bottom: 6px;">
                        <input type="checkbox" name="obrigatorio" value="1" checked id="obrig-{{ $assunto->id }}">
                        <label for="obrig-{{ $assunto->id }}" style="font-size: 0.75rem; font-weight: 600; color: #475569; cursor: pointer;">Obrigatório</label>
                    </div>
                    <div>
                        <button type="submit" class="btn-salvar-sm" style="padding: 6px 12px; font-size: 0.8125rem;">
                            + Adicionar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </td>
</tr>
