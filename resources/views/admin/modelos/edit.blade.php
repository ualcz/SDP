@extends('layouts.app')

@section('title', 'Editar Modelo - ' . $modelo->setor_sigla)
@section('tag', 'Administração')

@section('content')
<style>
    table {
        border-collapse: collapse;
        width: 100%;
        margin-top: 15px;
        margin-bottom: 20px;
    }
    th, td {
        border: 1px solid #ccc;
        padding: 8px;
        text-align: left;
    }
    th {
        background: #f5f5f5;
    }
    .form-group {
        margin-bottom: 12px;
    }
    .form-group label {
        display: block;
        font-weight: bold;
        margin-bottom: 4px;
    }
    .form-group input, .form-group textarea {
        width: 100%;
        max-width: 600px;
        padding: 6px;
        box-sizing: border-box;
    }
    .secao-bloco {
        border: 1px solid #ddd;
        padding: 16px;
        margin-top: 20px;
        background: #fff;
    }
</style>

<div>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
        <h2>Editar Modelo: {{ $modelo->setor_sigla }} - {{ $modelo->setor_nome }}</h2>
        <a href="{{ route('admin.modelos.index') }}">← Voltar para lista de modelos</a>
    </div>

    @if(session('success'))
        <div style="background: #e6f4ea; color: #137333; padding: 10px; margin-bottom: 15px; border: 1px solid #ceead6;">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div style="background: #fce8e6; color: #c5221f; padding: 10px; margin-bottom: 15px; border: 1px solid #fad2cf;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- 1. DADOS GERAIS DO MODELO --}}
    <div class="secao-bloco">
        <h3>Dados Gerais do Modelo</h3>
        <form action="{{ route('admin.modelos.update', $modelo->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="titulo">Título do Formulário:</label>
                <input type="text" id="titulo" name="titulo" value="{{ old('titulo', $modelo->titulo) }}" required>
            </div>

            <div style="display: flex; gap: 15px; max-width: 600px;">
                <div class="form-group" style="flex: 1;">
                    <label for="setor_sigla">Sigla do Setor:</label>
                    <input type="text" id="setor_sigla" name="setor_sigla" value="{{ old('setor_sigla', $modelo->setor_sigla) }}" required>
                </div>
                <div class="form-group" style="flex: 2;">
                    <label for="setor_nome">Nome Completo do Setor:</label>
                    <input type="text" id="setor_nome" name="setor_nome" value="{{ old('setor_nome', $modelo->setor_nome) }}" required>
                </div>
            </div>

            <div style="display: flex; gap: 15px; max-width: 600px;">
                <div class="form-group" style="flex: 1;">
                    <label for="email">E-mail do Setor:</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $modelo->email) }}">
                </div>
                <div class="form-group" style="flex: 1;">
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
                <small style="color: #666;">Essas notas são exibidas no rodapé da seção de objetos no PDF.</small>
            </div>

            <div class="form-group">
                <label style="display: inline-flex; align-items: center; gap: 6px; cursor: pointer;">
                    <input type="checkbox" name="ativo" value="1" {{ old('ativo', $modelo->ativo) ? 'checked' : '' }}>
                    <span>Modelo Ativo (disponível para seleção dos alunos)</span>
                </label>
            </div>

            <button type="submit" style="padding: 8px 16px; cursor: pointer;">Salvar Dados do Modelo</button>
        </form>
    </div>

    {{-- 2. ASSUNTOS DO MODELO COM OBSERVAÇÃO --}}
    <div class="secao-bloco">
        <h3>Assuntos (Objetos do Requerimento)</h3>
        <p style="color: #666;">Altere o código, descrição, observação e status de cada assunto diretamente abaixo:</p>

        <table>
            <thead>
                <tr>
                    <th style="width: 70px;">Cód.</th>
                    <th>Descrição do Assunto</th>
                    <th>Observação / Requisito do Assunto</th>
                    <th style="width: 70px;">Ordem</th>
                    <th style="width: 70px;">Ativo</th>
                    <th style="width: 150px;">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($modelo->assuntos as $assunto)
                    <tr>
                        <form action="{{ route('admin.assuntos.update', $assunto->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <td>
                                <input type="text" name="codigo" value="{{ $assunto->codigo }}" style="width: 100%; box-sizing: border-box;">
                            </td>
                            <td>
                                <input type="text" name="descricao" value="{{ $assunto->descricao }}" required style="width: 100%; box-sizing: border-box;">
                            </td>
                            <td>
                                <input type="text" name="observacao" value="{{ $assunto->observacao }}" placeholder="Ex: Necessita atestado médico" style="width: 100%; box-sizing: border-box;">
                            </td>
                            <td>
                                <input type="number" name="ordem" value="{{ $assunto->ordem }}" style="width: 100%; box-sizing: border-box;">
                            </td>
                            <td style="text-align: center;">
                                <input type="checkbox" name="ativo" value="1" {{ $assunto->ativo ? 'checked' : '' }}>
                            </td>
                            <td>
                                <button type="submit" style="cursor: pointer; padding: 4px 8px;">Salvar</button>
                        </form>
                        <form action="{{ route('admin.assuntos.destroy', $assunto->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Deseja realmente excluir este assunto?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="cursor: pointer; padding: 4px 8px; color: red;">Excluir</button>
                        </form>
                            </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">Nenhum assunto cadastrado para este modelo.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- FORMULÁRIO PARA ADICIONAR NOVO ASSUNTO --}}
        <div style="background: #fafafa; border: 1px dashed #ccc; padding: 14px; margin-top: 15px;">
            <h4 style="margin-top: 0;">+ Adicionar Novo Assunto</h4>
            <form action="{{ route('admin.modelos.assuntos.store', $modelo->id) }}" method="POST">
                @csrf
                <div style="display: flex; gap: 10px; flex-wrap: wrap; align-items: flex-end;">
                    <div style="width: 80px;">
                        <label style="display: block; font-size: 0.85rem;">Código:</label>
                        <input type="text" name="codigo" placeholder="Ex: 12" style="width: 100%; padding: 6px; box-sizing: border-box;">
                    </div>
                    <div style="flex: 2; min-width: 220px;">
                        <label style="display: block; font-size: 0.85rem;">Descrição:*</label>
                        <input type="text" name="descricao" placeholder="Ex: Declaração de Horário Individual" required style="width: 100%; padding: 6px; box-sizing: border-box;">
                    </div>
                    <div style="flex: 2; min-width: 220px;">
                        <label style="display: block; font-size: 0.85rem;">Observação / Requisito (opcional):</label>
                        <input type="text" name="observacao" placeholder="Ex: Necessita assinatura do coordenador" style="width: 100%; padding: 6px; box-sizing: border-box;">
                    </div>
                    <div>
                        <button type="submit" style="padding: 6px 14px; cursor: pointer; background: #000; color: #fff;">Adicionar Assunto</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
