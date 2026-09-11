{{-- FORMULÁRIO PARA ADICIONAR NOVO ASSUNTO --}}
<div style="background: #f9fafb; border: 1px dashed #cbd5e1; border-radius: 8px; padding: 18px; margin-top: 15px;">
    <h4 style="margin-top: 0; margin-bottom: 12px; font-size: 1rem; font-weight: 600; color: #1f2937;">
        Adicionar Novo Assunto
    </h4>
    <form action="{{ route('admin.setores.assuntos.store', $modelo->id) }}" method="POST">
        @csrf
        <div style="display: flex; gap: 12px; flex-wrap: wrap; align-items: flex-end;">
            <div style="width: 90px;">
                <label style="display: block; font-size: 0.8125rem; font-weight: 600; color: #4b5563; margin-bottom: 4px;">Código:</label>
                <input type="text" name="codigo" placeholder="Ex: 12" class="input-tabela">
            </div>
            <div style="flex: 2; min-width: 220px;">
                <label style="display: block; font-size: 0.8125rem; font-weight: 600; color: #4b5563; margin-bottom: 4px;">Descrição:*</label>
                <input type="text" name="descricao" placeholder="Ex: Declaração de Horário Individual" required class="input-tabela">
            </div>
            <div style="flex: 2; min-width: 220px;">
                <label style="display: block; font-size: 0.8125rem; font-weight: 600; color: #4b5563; margin-bottom: 4px;">Observação / Requisito (opcional):</label>
                <input type="text" name="observacao" placeholder="Ex: Necessita assinatura do coordenador" class="input-tabela">
            </div>
            <div>
                <button type="submit" class="btn-salvar" style="padding: 7px 16px;">
                    + Adicionar Assunto
                </button>
            </div>
        </div>
    </form>
</div>
