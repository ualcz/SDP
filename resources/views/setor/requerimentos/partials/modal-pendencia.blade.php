<x-modal id="modal-pendencia" title="Solicitar Alteração">

            <div  style="margin-top: 1rem;">
                <p>Solicite alterações ao aluno</p>
                <form action="{{ route('setor.requerimentos.atualizarStatus', [$setor->id, $requerimento->id]) }}"
                    method="POST" enctype="multipart/form-data" id="formCorrecao">
                    @csrf
                    @method('PATCH')


                    <div id="campo-mensagem" style="margin-top: 15px;">
                        <label for="observacao" class="info-label">Mensagem de Instruções:</label>
                        <textarea
                            name="observacao"
                            id="observacao"
                            class="form-control"
                            rows="3"
                            placeholder="Descreva o motivo ou o que o aluno precisa corrigir..."
                            {{ $requerimento->status == 'Indeferido' ? 'required' : '' }}
                        >{{ old('observacao') }}</textarea>


                        <div style="margin-bottom: 12px; margin-top:10px;">
                            <label for="solicita_novo_documento" class="info-label">Deseja solicitar o envio de um documento ao aluno?</label>
                            <select name="solicita_novo_documento" id="solicita_novo_documento" class="custom-select" style="width: 100%;" onchange="toggleCampoNomeDocumento()">
                                <option value="0" {{ old('solicita_novo_documento') == '0' ? 'selected' : '' }}>Não</option>
                                <option value="1" {{ old('solicita_novo_documento') == '1' ? 'selected' : '' }}>Sim, solicitar documento</option>
                            </select>
                        </div>

                        <div id="box-nome-documento" style="display: {{ old('solicita_novo_documento') == '1' ? 'block' : 'none' }};">
                            <label for="nome_documento_solicitado" class="info-label">Nome/Tipo do documento solicitado:</label>
                            <input
                                type="text"
                                name="nome_documento_solicitado"
                                id="nome_documento_solicitado"
                                class="select-status"
                                placeholder="Ex: Atestado Médico, Comprovante de Residência..."
                                value="{{ old('nome_documento_solicitado') }}"
                                style="width: 100%; border-radius: 6px; border: 1px solid #d1d5db; padding: 8px;"
                            >
                        </div>
                    </div>
                                        <div style="margin-top: 15px;">
                        <label for="arquivos_servidor" class="info-label">Anexar Documento | (opcional):</label>

                        <x-file-input
                            name="arquivos[]"
                            label=""
                            help-text="Selecione um ou mais arquivos para enviar."
                            id="arquivos_servidor"
                            multiple
                            accept=".pdf,.doc,.docx,.png,.jpg,.jpeg"
                            />

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
                Enviar solicitação de alteração
            </button>
        </x-slot:footer>
</x-modal>
