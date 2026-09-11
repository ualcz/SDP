{{-- 2. ASSUNTOS DO MODELO COM OBSERVAÇÃO E DOCUMENTOS --}}
<div class="secao-bloco">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
        <h3 style="margin: 0; font-size: 1.15rem; font-weight: 600; color: #1f2937;">
            2. Assuntos e Documentos Exigidos
        </h3>
    </div>

    <div style="overflow-x: auto;">
        <table class="tabela-assuntos">
            <thead>
                <tr>
                    <th style="width: 70px;">Cód.</th>
                    <th>Descrição do Assunto</th>
                    <th>Observação / Requisito</th>
                    <th style="width: 120px; text-align: center;">Documentos</th>
                    <th style="width: 65px; text-align: center;">Ordem</th>
                    <th style="width: 55px; text-align: center;">Ativo</th>
                    <th style="width: 150px; text-align: center;">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($modelo->assuntos as $assunto)
                    @php($formUpdateId = 'form-assunto-update-' . $assunto->id)
                    @php($formDeleteId = 'form-assunto-delete-' . $assunto->id)
                    @php($docsRowId = 'docs-assunto-' . $assunto->id)
                    @php($docsCount = $assunto->documentos->count())

                    <form id="{{ $formUpdateId }}" action="{{ route('admin.assuntos.update', $assunto->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                    </form>
                    <form id="{{ $formDeleteId }}" action="{{ route('admin.assuntos.destroy', $assunto->id) }}" method="POST" onsubmit="return confirm('Deseja realmente excluir este assunto e seus documentos vinculados?');">
                        @csrf
                        @method('DELETE')
                    </form>

                    <tr>
                        <td>
                            <input form="{{ $formUpdateId }}" type="text" name="codigo" value="{{ $assunto->codigo }}" class="input-tabela" placeholder="Ex: 01">
                        </td>
                        <td>
                            <input form="{{ $formUpdateId }}" type="text" name="descricao" value="{{ $assunto->descricao }}" required class="input-tabela">
                        </td>
                        <td>
                            <input form="{{ $formUpdateId }}" type="text" name="observacao" value="{{ $assunto->observacao }}" placeholder="Ex: Necessita atestado médico" class="input-tabela">
                        </td>
                        <td style="text-align: center;">
                            <button type="button" class="btn-docs {{ $docsCount > 0 ? 'has-docs' : '' }}" onclick="toggleDocumentos('{{ $docsRowId }}')" title="Gerenciar documentos deste assunto">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                </svg>
                                <span>{{ $docsCount }} Doc(s)</span>
                            </button>
                        </td>
                        <td style="text-align: center;">
                            <input form="{{ $formUpdateId }}" type="number" name="ordem" value="{{ $assunto->ordem }}" class="input-tabela" style="width: 55px; text-align: center;">
                        </td>
                        <td style="text-align: center;">
                            <input form="{{ $formUpdateId }}" type="checkbox" name="ativo" value="1" {{ $assunto->ativo ? 'checked' : '' }} title="Marque para manter ativo">
                        </td>
                        <td style="text-align: center;">
                            <div style="display: inline-flex; gap: 4px; align-items: center;">
                                <button form="{{ $formUpdateId }}" type="submit" class="btn-salvar-sm" title="Salvar alterações deste assunto">
                                    Salvar
                                </button>
                                <button form="{{ $formDeleteId }}" type="submit" class="btn-excluir-sm" title="Excluir este assunto">
                                    Excluir
                                </button>
                            </div>
                        </td>
                    </tr>

                    @include('admin.setor.partials.item-assunto-documentos', [
                        'assunto' => $assunto,
                        'docsRowId' => $docsRowId,
                        'docsCount' => $docsCount
                    ])
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 20px; color: #6b7280;">Nenhum assunto cadastrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @include('admin.setor.partials.form-novo-assunto', ['modelo' => $modelo])
</div>
