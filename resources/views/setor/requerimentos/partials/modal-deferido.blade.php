<x-modal id="modal-deferir/concluir" title="Deferir/Indeferir">

     <form action="{{ route('setor.requerimentos.atualizarStatus', [$setor->id, $requerimento->id]) }}" method="POST" enctype="multipart/form-data" id="formCorrecao">
            @csrf
            @method('PATCH')
        <label for="parecer" class="info-label">Resultado:</label>
        <div class="custom-select-wrapper">
            <select name="status" id="parecer" class="custom-select" required onchange="toggleMensagemIndeferido()">
                <option value="">Selecione...</option>
                <option value="Deferido" {{ $requerimento->status == 'Deferido' ? 'selected' : '' }}>Deferido</option>
                <option value="Indeferido" {{ $requerimento->status == 'Indeferido' ? 'selected' : '' }}>Indeferido</option>
            </select>
        </div>
        <br>
        <label for="observacao" class="info-label">Resposta | Parecer:</label>
        <textarea
            name="observacao"
            id="observacao"
            class="form-control"
            rows="3"
            placeholder="Descreva o parecer ou a resposta ao requerimento..."
            required
        >{{ old('observacao') }}</textarea>
        <br>
        <br>
        <div>
            <label for="arquivos_servidor" class="info-label">Anexar Documento / Parecer / Despacho (opcional):</label>
            <x-file-input
                name="arquivos[]"
                label=''
                help-text="Você pode anexar um ou mais documentos (PDF, imagens, documentos Word) para ficarem registrados no histórico desta tramitação."
                id="arquivos_servidor"
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
                form="formCorrecao"
                class="btn-atualizar"
            >
               Enviar parecer
            </button>
        </x-slot:footer>
</x-modal>
