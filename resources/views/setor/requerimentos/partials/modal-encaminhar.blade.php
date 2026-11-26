<x-modal id="modal-encaminhar" title="Tramitar requerimento">

    @if($requerimento->setor_retorno_id)
        <div>
            <p>
                Registre a resposta e devolva o requerimento
                ao setor que o encaminhou.
            </p>

            <form
                id="form-resposta-encaminhamento"
                action="{{ route('setor.requerimentos.responderEncaminhamento', [$setor->id, $requerimento->id]) }}"
                method="POST"
                enctype="multipart/form-data"
            >
                @csrf

                <label for="resposta_encaminhamento" class="info-label">
                    Resposta
                </label>

                <textarea
                    name="observacao"
                    id="resposta_encaminhamento"
                    class="form-control"
                    rows="4"
                    required
                >{{ old('observacao') }}</textarea>

                <div style="margin-top: 12px;">
                    <label for="arquivos_resposta" class="info-label">
                        Anexar documentos (opcional)
                    </label>

                    <input
                        type="file"
                        name="arquivos[]"
                        id="arquivos_resposta"
                        class="form-control file-input"
                        multiple
                        accept=".pdf,.doc,.docx,.png,.jpg,.jpeg"
                    >
                </div>
            </form>
        </div>

        <x-slot:footer>
            <button type="button" data-modal-close class="btn-atualizar">
                Cancelar
            </button>

            <button
                type="submit"
                form="form-resposta-encaminhamento"
                class="btn-atualizar"
            >
                Enviar resposta e devolver
            </button>
        </x-slot:footer>


    @else
        <div>
            <h3 class="card-titulo">Encaminhar para outro setor</h3>

            <form
                id="form-encaminhar"
                action="{{ route('setor.requerimentos.encaminhar', [$setor->id, $requerimento->id]) }}"
                method="POST"
                enctype="multipart/form-data"
            >
                @csrf

                <label for="setor_destino_id" class="info-label">
                    Setor de destino
                </label>

                <select
                    name="setor_destino_id"
                    id="setor_destino_id"
                    class="select-status"
                    required
                >
                    <option value="">Selecione um setor</option>

                    @foreach($setoresDestino as $setorDestino)
                        <option
                            value="{{ $setorDestino->id }}"
                            {{ old('setor_destino_id') == $setorDestino->id ? 'selected' : '' }}
                        >
                            {{ $setorDestino->setor_nome }}
                            ({{ $setorDestino->setor_sigla }})
                        </option>
                    @endforeach
                </select>

                <label
                    for="observacao_encaminhamento"
                    class="info-label"
                    style="margin-top: 12px;"
                >
                    Orientação para o setor
                </label>

                <textarea
                    name="observacao"
                    id="observacao_encaminhamento"
                    class="form-control"
                    rows="4"
                    required
                >{{ old('observacao') }}</textarea>

                <div style="margin-top: 12px;">
                    <label for="arquivos_encaminhamento" class="info-label">
                        Anexar documentos (opcional)
                    </label>

                    <input
                        type="file"
                        name="arquivos[]"
                        id="arquivos_encaminhamento"
                        class="form-control file-input"
                        multiple
                        accept=".pdf,.doc,.docx,.png,.jpg,.jpeg"
                    >
                </div>
            </form>
        </div>

        <x-slot:footer>
            <button type="button" data-modal-close class="btn-atualizar">
                Cancelar
            </button>

            <button
                type="submit"
                form="form-encaminhar"
                class="btn-atualizar"
            >
                Encaminhar requerimento
            </button>
        </x-slot:footer>
    @endif





</x-modal>
