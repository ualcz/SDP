<x-modal id="modal-encaminhar" title="Tramitar requerimento">

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
                class="custom-select"
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



@if(session('sucesso'))
        <div class="alert-sucesso">
            {{ session('sucesso') }}
        </div>
    @endif

    @if($requerimento->setor_retorno_id)
        <x-loading-overlay
            form-id="form-resposta-encaminhamento"
            :mensagens="[
                'Atualizando status...',
                'Enviando email para o aluno...',
                'Atualizando sistema...',
                'Só mais um instante...'
            ]"
        />
    @else
        <x-loading-overlay
            form-id="form-encaminhar"
            :mensagens="[
                'Atualizando status...',
                'Enviando email para o aluno...',
                'Atualizando sistema...',
                'Só mais um instante...'
            ]"
        />
    @endif


</x-modal>
