{{-- LINHA EXPANSÍVEL: GERENCIAMENTO DE DOCUMENTOS DO ASSUNTO --}}
<tr id="{{ $docsRowId }}" class="tr-documentos" style="display: none; background: #f8fafc;">
    <td colspan="7" style="padding: 14px 16px;">
        <div class="box-docs-nested">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                <h5 style="margin: 0; font-size: 0.925rem; font-weight: 700; color: #1e293b;">
                    Documentos / Anexos Exigidos para: <span style="color: #059669;">"{{ $assunto->descricao }}"</span>
                </h5>
                <button type="button" onclick="toggleDocumentos('{{ $docsRowId }}')" class="btn-voltar" style="padding: 2px 8px; font-size: 0.75rem;">
                    Fechar &times;
                </button>
            </div>

            @if($docsCount > 0)
                <table class="tabela-docs-nested">
                    <thead>
                        <tr>
                            <th>Nome do Documento</th>
                            <th>Instrução / Descrição de Ajuda ao Aluno</th>
                            <th style="width: 140px;">Formatos Aceitos</th>
                            <th style="width: 90px; text-align: center;">Obrigatório</th>
                            <th style="width: 120px; text-align: center;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($assunto->documentos as $doc)
                            @php($formDocUpdateId = 'form-doc-update-' . $doc->id)
                            @php($formDocDeleteId = 'form-doc-delete-' . $doc->id)

                            <form id="{{ $formDocUpdateId }}" action="{{ route('admin.documentos.update', $doc->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                            </form>
                            <form id="{{ $formDocDeleteId }}" action="{{ route('admin.documentos.destroy', $doc->id) }}" method="POST" onsubmit="return confirm('Deseja excluir este documento exigido?');">
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
                                <td>
                                    <input form="{{ $formDocUpdateId }}" type="text" name="tipos_aceitos" value="{{ $doc->tipos_aceitos }}" class="input-tabela" style="font-size: 0.8125rem;" placeholder="pdf,jpg,png">
                                </td>
                                <td style="text-align: center;">
                                    <input form="{{ $formDocUpdateId }}" type="checkbox" name="obrigatorio" value="1" {{ $doc->obrigatorio ? 'checked' : '' }} title="Marque se for obrigatório">
                                </td>
                                <td style="text-align: center;">
                                    <div style="display: inline-flex; gap: 4px; align-items: center;">
                                        <button form="{{ $formDocUpdateId }}" type="submit" class="btn-salvar-sm" style="padding: 4px 8px; font-size: 0.75rem;" title="Salvar este documento">
                                            Salvar
                                        </button>
                                        <button form="{{ $formDocDeleteId }}" type="submit" class="btn-excluir-sm" style="padding: 4px 8px; font-size: 0.75rem;" title="Excluir este documento">
                                            Excluir
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p style="font-size: 0.8125rem; color: #64748b; margin: 0 0 10px 0;">
                    <em>Nenhum documento exigido cadastrado para este assunto (os anexos serão livres/opcionais no formulário do aluno).</em>
                </p>
            @endif

            {{-- Formulário para adicionar documento a este assunto --}}
            <div style="background: #ffffff; border: 1px dashed #94a3b8; border-radius: 6px; padding: 10px 14px; margin-top: 6px;">
                <strong style="display: block; font-size: 0.8125rem; color: #334155; margin-bottom: 6px;">
                    + Adicionar Documento / Anexo a este Assunto:
                </strong>
                <form action="{{ route('admin.documentos.store', $assunto->id) }}" method="POST">
                    @csrf
                    <div style="display: flex; gap: 8px; flex-wrap: wrap; align-items: flex-end;">
                        <div style="flex: 2; min-width: 170px;">
                            <label style="display: block; font-size: 0.75rem; font-weight: 600; color: #475569; margin-bottom: 2px;">Nome do Documento:*</label>
                            <input type="text" name="nome" placeholder="Ex: Histórico Escolar" required class="input-tabela" style="font-size: 0.8125rem;">
                        </div>
                        <div style="flex: 3; min-width: 200px;">
                            <label style="display: block; font-size: 0.75rem; font-weight: 600; color: #475569; margin-bottom: 2px;">Instrução / Ajuda (opcional):</label>
                            <input type="text" name="descricao" placeholder="Ex: Emitido pela biblioteca do campus..." class="input-tabela" style="font-size: 0.8125rem;">
                        </div>
                        <div style="width: 120px;">
                            <label style="display: block; font-size: 0.75rem; font-weight: 600; color: #475569; margin-bottom: 2px;">Extensões:</label>
                            <input type="text" name="tipos_aceitos" value="pdf,jpg,jpeg,png" class="input-tabela" style="font-size: 0.8125rem;">
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
        </div>
    </td>
</tr>
