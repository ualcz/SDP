@extends('layouts.app')

@section('title', 'Editar Modelo - ' . $modelo->setor_sigla)
@section('tag', 'Administração')

@section('content')
<style>
    .admin-card {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        padding: 24px;
        margin-bottom: 24px;
    }
    .secao-bloco {
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 20px;
        margin-top: 20px;
        background: #ffffff;
    }
    .tabela-assuntos {
        border-collapse: collapse;
        width: 100%;
        margin-top: 15px;
        margin-bottom: 20px;
    }
    .tabela-assuntos th, .tabela-assuntos td {
        border: 1px solid #e5e7eb;
        padding: 10px 12px;
        text-align: left;
    }
    .tabela-assuntos th {
        background: #f9fafb;
        font-weight: 600;
        color: #374151;
        font-size: 0.875rem;
    }
    .tabela-assuntos tr:hover {
        background-color: #fafafa;
    }
    .input-tabela {
        width: 100%;
        padding: 6px 10px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 0.875rem;
        box-sizing: border-box;
    }
    .input-tabela:focus {
        border-color: #2563eb;
        outline: none;
        box-shadow: 0 0 0 2px rgba(37,99,235,0.15);
    }
    .form-group {
        margin-bottom: 14px;
    }
    .form-group label {
        display: block;
        font-weight: 600;
        font-size: 0.875rem;
        color: #374151;
        margin-bottom: 4px;
    }
    .form-group input, .form-group textarea {
        width: 100%;
        max-width: 600px;
        padding: 8px 12px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 0.875rem;
        box-sizing: border-box;
    }
    .form-group input:focus, .form-group textarea:focus {
        border-color: #2563eb;
        outline: none;
        box-shadow: 0 0 0 2px rgba(37,99,235,0.15);
    }
    .btn-salvar {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 18px;
        background-color: #2563eb;
        color: #fff;
        border: none;
        border-radius: 6px;
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.2s;
    }
    .btn-salvar:hover {
        background-color: #1d4ed8;
    }
    .btn-salvar-sm {
        padding: 6px 12px;
        background-color: #16a34a;
        color: #fff;
        border: none;
        border-radius: 6px;
        font-size: 0.8125rem;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.2s;
    }
    .btn-salvar-sm:hover {
        background-color: #15803d;
    }
    .btn-excluir-sm {
        padding: 6px 12px;
        background-color: #ef4444;
        color: #fff;
        border: none;
        border-radius: 6px;
        font-size: 0.8125rem;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.2s;
    }
    .btn-excluir-sm:hover {
        background-color: #dc2626;
    }
    .btn-voltar {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 0.875rem;
        border: 1px solid #d1d5db;
        color: #374151;
        text-decoration: none;
        background: #fff;
    }
    .btn-voltar:hover {
        background: #f3f4f6;
    }
</style>

<div class="admin-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div>
            <h2 style="font-size: 1.5rem; font-weight: 700; margin: 0; color: #111827;">
                Editar Modelo: {{ $modelo->setor_sigla }} &mdash; {{ $modelo->setor_nome }}
            </h2>
            <p style="color: #6b7280; font-size: 0.875rem; margin: 4px 0 0 0;">
                Atualize as informações do modelo e gerencie os assuntos com suas observações.
            </p>
        </div>
        <a href="{{ route('admin.modelos.index') }}" class="btn-voltar">
            &larr; Voltar para lista de modelos
        </a>
    </div>

    @if(session('success'))
        <div style="background: #e6f4ea; color: #137333; padding: 12px 16px; margin-bottom: 20px; border: 1px solid #ceead6; border-radius: 6px;">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div style="background: #fce8e6; color: #c5221f; padding: 12px 16px; margin-bottom: 20px; border: 1px solid #fad2cf; border-radius: 6px;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- 1. DADOS GERAIS DO MODELO --}}
    <div class="secao-bloco">
        <h3 style="margin-top: 0; margin-bottom: 14px; font-size: 1.15rem; font-weight: 600; color: #1f2937;">
            1. Dados Gerais do Modelo
        </h3>
        <form action="{{ route('admin.modelos.update', $modelo->id) }}" method="POST">
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

    {{-- 2. ASSUNTOS DO MODELO COM OBSERVAÇÃO --}}
    <div class="secao-bloco">
        <h3 style="margin-top: 0; margin-bottom: 6px; font-size: 1.15rem; font-weight: 600; color: #1f2937;">
            2. Assuntos (Objetos do Requerimento)
        </h3>
        <p style="color: #6b7280; font-size: 0.875rem; margin-top: 0; margin-bottom: 16px;">
            Altere o código, descrição, observação e status de cada assunto diretamente na tabela abaixo e clique em <strong>Salvar</strong> na linha correspondente:
        </p>

        <div style="overflow-x: auto;">
            <table class="tabela-assuntos">
                <thead>
                    <tr>
                        <th style="width: 80px;">Cód.</th>
                        <th>Descrição do Assunto</th>
                        <th>Observação / Requisito do Assunto</th>
                        <th style="width: 70px; text-align: center;">Ordem</th>
                        <th style="width: 60px; text-align: center;">Ativo</th>
                        <th style="width: 170px; text-align: center;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($modelo->assuntos as $assunto)
                        @php($formUpdateId = 'form-assunto-update-' . $assunto->id)
                        @php($formDeleteId = 'form-assunto-delete-' . $assunto->id)

                        <form id="{{ $formUpdateId }}" action="{{ route('admin.assuntos.update', $assunto->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                        </form>
                        <form id="{{ $formDeleteId }}" action="{{ route('admin.assuntos.destroy', $assunto->id) }}" method="POST" onsubmit="return confirm('Deseja realmente excluir este assunto?');">
                            @csrf
                            @method('DELETE')
                        </form>

                        <tr>
                            <td>
                                <input form="{{ $formUpdateId }}" type="text" name="codigo" value="{{ $assunto->codigo }}" class="input-tabela" placeholder="Ex: 01">
                            </td>
                            <td>
                                <input form="{{ $formUpdateId }}" type="text" name="descricao" value="{{ $assunto->descricao }}" required class="input-tabela">
                            </td>
                            <td>
                                <input form="{{ $formUpdateId }}" type="text" name="observacao" value="{{ $assunto->observacao }}" placeholder="Ex: Necessita atestado médico" class="input-tabela">
                            </td>
                            <td style="text-align: center;">
                                <input form="{{ $formUpdateId }}" type="number" name="ordem" value="{{ $assunto->ordem }}" class="input-tabela" style="width: 60px; text-align: center;">
                            </td>
                            <td style="text-align: center;">
                                <input form="{{ $formUpdateId }}" type="checkbox" name="ativo" value="1" {{ $assunto->ativo ? 'checked' : '' }} title="Marque para manter ativo">
                            </td>
                            <td style="text-align: center;">
                                <div style="display: inline-flex; gap: 6px; align-items: center;">
                                    <button form="{{ $formUpdateId }}" type="submit" class="btn-salvar-sm" title="Salvar alterações deste assunto">
                                        Salvar
                                    </button>
                                    <button form="{{ $formDeleteId }}" type="submit" class="btn-excluir-sm" title="Excluir este assunto">
                                        Excluir
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 20px; color: #6b7280;">Nenhum assunto cadastrado para este modelo.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- FORMULÁRIO PARA ADICIONAR NOVO ASSUNTO --}}
        <div style="background: #f9fafb; border: 1px dashed #cbd5e1; border-radius: 8px; padding: 18px; margin-top: 15px;">
            <h4 style="margin-top: 0; margin-bottom: 12px; font-size: 1rem; font-weight: 600; color: #1f2937;">
                + Adicionar Novo Assunto a este Modelo
            </h4>
            <form action="{{ route('admin.modelos.assuntos.store', $modelo->id) }}" method="POST">
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
    </div>
</div>
@endsection
