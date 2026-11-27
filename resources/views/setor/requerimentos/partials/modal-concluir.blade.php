<x-modal id="modal-concluir" title="Concluir Requerimento">
    <form
    action="{{ route('setor.requerimentos.atualizarStatus', [$setor->id, $requerimento->id]) }}"
    method="POST"
        enctype="multipart/form-data"
        id="formConcluir"
        >
        @csrf
        @method('PATCH')

        <input type="hidden" name="status" value="Concluído">

        <br>
        <label for="observacao_concluir" class="info-label">Observações:</label>
        <textarea
        name="observacao"
            id="observacao_concluir"
            class="form-control"
            rows="3"
            placeholder="Digite aqui observações caso seja necessário..."
        >{{ old('observacao') }}</textarea>
        <br>
        <br>
        <div>
            <label for="arquivos_concluir" class="info-label">Anexar Documento / Parecer / Despacho (opcional):</label>
            <x-file-input
            name="arquivos[]"
            label=""
            help-text="Você pode anexar um ou mais documentos (PDF, imagens, documentos Word) para ficarem registrados no histórico desta tramitação."
            id="arquivos_concluir"
            class="form-control file-input"
            multiple
            accept=".pdf,.doc,.docx,.png,.jpg,.jpeg"
            />
        </div>
    </form>

    <x-slot:footer>
        <button type="button" data-modal-close class="btn-atualizar">
            Cancelar
        </button>

        <button
            type="submit"
            form="formConcluir"
            class="btn-atualizar"
        >
            Concluir requerimento
        </button>
    </x-slot:footer>
</x-modal>

@if(session('sucesso'))
    <div class="alert-sucesso">
        {{ session('sucesso') }}
    </div>
@endif
    <x-loading-overlay
    form-id="formConcluir"
    :mensagens="[
        'Atualizando status...',
        'Enviando email para o aluno...',
        'Atualizando sistema...',
        'Só mais um instante...'
    ]"
/>
