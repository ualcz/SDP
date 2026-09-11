{{-- 1. DADOS GERAIS DO SETOR --}}
<div class="secao-bloco">
    <h3 style="margin-top: 0; margin-bottom: 14px; font-size: 1.15rem; font-weight: 600; color: #1f2937;">
        1. Dados Gerais 
    </h3>
    <form action="{{ route('admin.setores.update', $modelo->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="titulo">Título do Formulário:*</label>
            <input type="text" id="titulo" name="titulo" value="{{ old('titulo', $modelo->titulo) }}" required>
        </div>

        <div style="display: flex; gap: 15px; max-width: 600px; flex-wrap: wrap;">
            <div class="form-group" style="flex: 1; min-width: 140px;">
                <label for="setor_sigla">Sigla do Setor:*</label>
                <input type="text" id="setor_sigla" name="setor_sigla" value="{{ old('setor_sigla', $modelo->setor_sigla) }}" required>
            </div>
            <div class="form-group" style="flex: 2; min-width: 200px;">
                <label for="setor_nome">Nome Completo do Setor:*</label>
                <input type="text" id="setor_nome" name="setor_nome" value="{{ old('setor_nome', $modelo->setor_nome) }}" required>
            </div>
        </div>

        <div style="display: flex; gap: 15px; max-width: 600px; flex-wrap: wrap;">
            <div class="form-group" style="flex: 1; min-width: 200px;">
                <label for="email">E-mail do Setor:</label>
                <input type="email" id="email" name="email" value="{{ old('email', $modelo->email) }}">
            </div>
            <div class="form-group" style="flex: 1; min-width: 140px;">
                <label for="processo_prefixo">Prefixo do Processo:</label>
                <input type="text" id="processo_prefixo" name="processo_prefixo" value="{{ old('processo_prefixo', $modelo->processo_prefixo) }}">
            </div>
        </div>

        <div class="form-group">
            <label for="rodape_contato">Contato no Rodapé do PDF (opcional):</label>
            <input type="text" id="rodape_contato" name="rodape_contato" value="{{ old('rodape_contato', $modelo->rodape_contato) }}">
        </div>

        <div class="form-group">
            <label for="observacoes_texto">Observações Gerais do Modelo (uma por linha):</label>
            <textarea id="observacoes_texto" name="observacoes_texto" rows="4">{{ old('observacoes_texto', implode("\n", $modelo->observacoes ?? [])) }}</textarea>
            <small style="color: #6b7280; display: block; margin-top: 4px;">Essas notas são exibidas no rodapé da seção de objetos no PDF.</small>
        </div>

        <div class="form-group">
            <label style="display: inline-flex; align-items: center; gap: 8px; cursor: pointer;">
                <input type="checkbox" name="ativo" value="1" {{ old('ativo', $modelo->ativo) ? 'checked' : '' }} style="width: auto;">
                <span style="font-weight: 500;">Modelo Ativo (disponível para seleção dos alunos)</span>
            </label>
        </div>

        <button type="submit" class="btn-salvar">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
            Salvar Dados do Modelo
        </button>
    </form>
</div>
