<x-modal id="modal-pendencia" title="Solicitar Alteração">

            <div class="card-painel" style="margin-top: 1rem;">
                <h3 class="card-titulo">Atualizar Status</h3>
                <p>Atualize o status do requerimento conforme necessário.</p>
                <hr style="border: 0; border-top: 1px solid #e5e7eb; margin: 1.25rem 0 1rem 0;">
                <form action="{{ route('setor.requerimentos.atualizarStatus', [$setor->id, $requerimento->id]) }}" method="POST" enctype="multipart/form-data" id="formCorrecao">
                    @csrf
                    @method('PATCH')

                    <label for="status" class="info-label">Atualizar Status do Requerimento</label>
                    <div class="form-status">
                        <select name="status" id="status" class="select-status" required onchange="toggleMensagemIndeferido()">
                            <option value="Em Análise" {{ $requerimento->status == 'Em Análise' ? 'selected' : '' }}>Em Análise</option>
                            <option value="Indeferido" {{ $requerimento->status == 'Indeferido' ? 'selected' : '' }}>Indeferido (Devolver ao aluno)</option>
                            <option value="Concluído" {{ $requerimento->status == 'Concluído' ? 'selected' : '' }}>Concluído</option>
                            <option value="Encerrado" {{ $requerimento->status == 'Encerrado' ? 'selected' : '' }}>Encerrado</option>
                        </select>

                        <button type="submit" class="btn-atualizar">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 14h-2v-2h2v2zm0-4h-2V7h2v5z"/>
                            </svg>
                            Atualizar Status
                        </button>
                    </div>

                    <div style="margin-top: 15px;">
                        <label for="arquivos_servidor" class="info-label">Anexar Documento / Parecer / Despacho (opcional):</label>
                        <input type="file" name="arquivos[]" id="arquivos_servidor" class="form-control file-input" multiple accept=".pdf,.doc,.docx,.png,.jpg,.jpeg">
                        <small style="color: #6b7280; font-size: 0.78rem; display: block; margin-top: 4px;">Você pode anexar um ou mais documentos (PDF, imagens, documentos Word) para ficarem registrados no histórico desta tramitação.</small>
                    </div>

                    <div id="campo-mensagem" style="display: {{ $requerimento->status == 'Indeferido' ? 'block' : 'none' }}; margin-top: 15px;">
                        <label for="observacao" class="info-label">Instrução de correção / Motivo do Indeferimento:</label>
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
                            <select name="solicita_novo_documento" id="solicita_novo_documento" class="select-status" style="width: 100%;" onchange="toggleCampoNomeDocumento()">
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
