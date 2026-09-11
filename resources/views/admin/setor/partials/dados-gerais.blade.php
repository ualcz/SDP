{{-- 1. DADOS GERAIS DO SETOR --}}
<div class="secao-bloco">
    <div style="margin-bottom: 16px;">
        <h3 style="margin: 0 0 4px 0; font-size: 1.15rem; font-weight: 700; color: #1e293b;">
            1. Dados Gerais do Setor
        </h3>
    </div>

    <form action="{{ route('admin.setores.update', $modelo->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid-dados-gerais">
            {{-- Sigla do Setor --}}
            <div class="form-group col-span-3">
                <label for="setor_sigla">Sigla do Setor:*</label>
                <input type="text" id="setor_sigla" name="setor_sigla" value="{{ old('setor_sigla', $modelo->setor_sigla) }}" placeholder="Ex: CORES" required>
            </div>

            {{-- Nome Completo do Setor --}}
            <div class="form-group col-span-9">
                <label for="setor_nome">Nome Completo do Setor:*</label>
                <input type="text" id="setor_nome" name="setor_nome" value="{{ old('setor_nome', $modelo->setor_nome) }}" placeholder="Ex: Coordenação de Registros Escolares" required>
            </div>

            {{-- Título do Formulário --}}
            <div class="form-group col-span-12">
                <label for="titulo">Título do Formulário:*</label>
                <input type="text" id="titulo" name="titulo" value="{{ old('titulo', $modelo->titulo) }}" placeholder="Ex: Requerimento - Registro Escolar (CORES)" required>
            </div>

            {{-- E-mail de Recebimento --}}
            <div class="form-group col-span-6">
                <label for="email">E-mail Oficial do Setor:*</label>
                <input type="email" id="email" name="email" value="{{ old('email', $modelo->email) }}" placeholder="protocolos.seabra@ifba.edu.br">
            </div>

            {{-- Prefixo do Processo --}}
            <div class="form-group col-span-3">
                <label for="processo_prefixo">Prefixo do Processo:</label>
                <input type="text" id="processo_prefixo" name="processo_prefixo" value="{{ old('processo_prefixo', $modelo->processo_prefixo) }}" placeholder="23720">
            </div>

            {{-- Contato no Rodapé do PDF --}}
            <div class="form-group col-span-3">
                <label for="rodape_contato">Contato no Rodapé do PDF:</label>
                <input type="text" id="rodape_contato" name="rodape_contato" value="{{ old('rodape_contato', $modelo->rodape_contato) }}" placeholder="Ex: Ramal ou e-mail">
            </div>

            {{-- Status Ativo --}}
            <div class="form-group col-span-12" style="margin-top: 4px;">
                <label class="card-toggle-ativo">
                    <input type="checkbox" name="ativo" value="1" {{ old('ativo', $modelo->ativo) ? 'checked' : '' }} style="width: 18px; height: 18px; cursor: pointer; accent-color: #2563eb;">
                    <div>
                        <strong style="display: block; font-size: 0.875rem; color: #1e293b;">Setor Ativo</strong>
                        <span style="font-size: 0.75rem; color: #64748b;">Quando desmarcado, este formulário não aparecerá para os alunos.</span>
                    </div>
                </label>
            </div>

            {{-- Botão de Salvar --}}
            <div class="col-span-12" style="margin-top: 10px;">
                <button type="submit" class="btn-salvar">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Salvar Dados Gerais
                </button>
            </div>
        </div>
    </form>
</div>
