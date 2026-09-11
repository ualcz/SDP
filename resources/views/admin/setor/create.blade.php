@extends('layouts.app')

@section('title', 'Novo Setor - SDP')
@section('tag', 'Administração')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-setor.css') }}">
@endpush

@section('content')
<div class="admin-setor-container">

    {{-- CABEÇALHO --}}
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div>
            <h2 style="font-size: 1.5rem; font-weight: 700; margin: 0; color: #111827;">Novo Setor</h2>
            <p style="font-size: 0.875rem; color: #6b7280; margin: 4px 0 0 0;">Preencha os dados para cadastrar um novo setor no sistema.</p>
        </div>
        <a href="{{ route('admin.setores.index') }}" class="btn-voltar">
            &larr; Voltar à lista
        </a>
    </div>

    {{-- ERROS DE VALIDAÇÃO --}}
    @if($errors->any())
        <div style="background: #fef2f2; color: #991b1b; padding: 12px 16px; margin-bottom: 20px; border: 1px solid #fecaca; border-radius: 6px;">
            <strong>Corrija os erros abaixo:</strong>
            <ul style="margin: 8px 0 0 20px; padding: 0;">
                @foreach($errors->all() as $erro)
                    <li>{{ $erro }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- FORMULÁRIO --}}
    <div class="secao-bloco">
        <div style="margin-bottom: 20px;">
            <h3 style="margin: 0; font-size: 1.15rem; font-weight: 700; color: #1e293b;">
                Dados do Setor
            </h3>
        </div>

        <form action="{{ route('admin.setores.store') }}" method="POST">
            @csrf

            <div class="grid-dados-gerais">

                {{-- Sigla --}}
                <div class="form-group col-span-3">
                    <label for="setor_sigla">Sigla do Setor: <span style="color:#dc2626;">*</span></label>
                    <input type="text"
                           id="setor_sigla"
                           name="setor_sigla"
                           value="{{ old('setor_sigla') }}"
                           placeholder="Ex: CORES"
                           required>
                </div>

                {{-- Nome Completo --}}
                <div class="form-group col-span-9">
                    <label for="setor_nome">Nome Completo do Setor: <span style="color:#dc2626;">*</span></label>
                    <input type="text"
                           id="setor_nome"
                           name="setor_nome"
                           value="{{ old('setor_nome') }}"
                           placeholder="Ex: Coordenação de Registros Escolares"
                           required>
                </div>

                {{-- Título do Formulário --}}
                <div class="form-group col-span-12">
                    <label for="titulo">Título do Formulário: <span style="color:#dc2626;">*</span></label>
                    <input type="text"
                           id="titulo"
                           name="titulo"
                           value="{{ old('titulo') }}"
                           placeholder="Ex: Requerimento - Registro Escolar (CORES)"
                           required>
                </div>

                {{-- E-mail --}}
                <div class="form-group col-span-6">
                    <label for="email">E-mail Oficial do Setor:</label>
                    <input type="email"
                           id="email"
                           name="email"
                           value="{{ old('email') }}"
                           placeholder="setor@ifba.edu.br">
                </div>

                {{-- Prefixo do Processo --}}
                <div class="form-group col-span-3">
                    <label for="processo_prefixo">Prefixo do Processo:</label>
                    <input type="text"
                           id="processo_prefixo"
                           name="processo_prefixo"
                           value="{{ old('processo_prefixo', '23720') }}"
                           placeholder="23720">
                </div>

                {{-- Rodapé PDF --}}
                <div class="form-group col-span-3">
                    <label for="rodape_contato">Contato no Rodapé do PDF:</label>
                    <input type="text"
                           id="rodape_contato"
                           name="rodape_contato"
                           value="{{ old('rodape_contato') }}"
                           placeholder="Ex: Ramal ou e-mail">
                </div>

                {{-- Status Ativo --}}
                <div class="form-group col-span-12" style="margin-top: 4px;">
                    <label class="card-toggle-ativo">
                        <input type="checkbox"
                               name="ativo"
                               value="1"
                               {{ old('ativo', '1') ? 'checked' : '' }}
                               style="width: 18px; height: 18px; cursor: pointer; accent-color: #2563eb;">
                        <div>
                            <strong style="display: block; font-size: 0.875rem; color: #1e293b;">Setor Ativo</strong>
                            <span style="font-size: 0.75rem; color: #64748b;">Quando marcado, este formulário ficará disponível para os alunos.</span>
                        </div>
                    </label>
                </div>

                {{-- Botões --}}
                <div class="col-span-12" style="display: flex; gap: 12px; justify-content: flex-end; margin-top: 8px; padding-top: 16px; border-top: 1px solid #e5e7eb;">
                    <a href="{{ route('admin.setores.index') }}"
                       style="padding: 8px 20px; border-radius: 6px; border: 1px solid #d1d5db; color: #374151; background: #fff; font-size: 0.875rem; font-weight: 600; text-decoration: none;">
                        Cancelar
                    </a>
                    <button type="submit"
                            style="padding: 8px 24px; border-radius: 6px; background: #2563eb; color: #fff; font-size: 0.875rem; font-weight: 700; border: none; cursor: pointer;">
                        ＋ Criar Setor
                    </button>
                </div>

            </div>
        </form>
    </div>

</div>
@endsection
