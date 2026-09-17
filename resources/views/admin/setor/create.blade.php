@extends('layouts.app')

@section('title', 'Novo Setor - SDP')
@section('tag', 'Administração')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin-setor.css') }}">
<style>
    .card-setor-create {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 24px;
        max-width: 860px;
        margin: 0 auto;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
    }

    .form-grid-setor {
        display: grid;
        grid-template-columns: repeat(12, 1fr);
        gap: 16px;
    }

    .col-span-12 { grid-column: span 12; }
    .col-span-9  { grid-column: span 9; }
    .col-span-6  { grid-column: span 6; }
    .col-span-3  { grid-column: span 3; }

    @media (max-width: 640px) {
        .col-span-9, .col-span-6, .col-span-3 {
            grid-column: span 12;
        }
    }

    .form-group label {
        display: block;
        font-size: 0.8125rem;
        font-weight: 600;
        color: #334155;
        margin-bottom: 4px;
    }

    .form-group input {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 0.875rem;
        box-sizing: border-box;
        transition: border-color 0.15s, box-shadow 0.15s;
    }

    .form-group input:focus {
        border-color: #059669;
        outline: none;
        box-shadow: 0 0 0 2px rgba(5, 150, 105, 0.15);
    }

    .btn-submit-setor {
        background-color: #059669;
        color: #ffffff;
        padding: 8px 22px;
        font-size: 0.875rem;
        font-weight: 600;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        transition: background-color 0.15s;
    }

    .btn-submit-setor:hover {
        background-color: #047857;
    }

    .btn-cancelar {
        background: #f1f5f9;
        color: #475569;
        padding: 8px 18px;
        font-size: 0.875rem;
        font-weight: 500;
        border-radius: 6px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        transition: background-color 0.15s;
    }

    .btn-cancelar:hover {
        background: #e2e8f0;
        color: #1e293b;
    }
</style>

<div class="card-setor-create">

    {{-- CABEÇALHO --}}
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 14px; border-bottom: 1px solid #f1f5f9;">
        <h2 style="font-size: 1.35rem; font-weight: 700; margin: 0; color: #1e293b;">Novo Setor</h2>
        <a href="{{ route('admin.setores.index') }}" class="btn-voltar">
            &larr; Voltar
        </a>
    </div>

    {{-- ERROS DE VALIDAÇÃO --}}
    @if($errors->any())
        <div style="background: #fef2f2; color: #991b1b; padding: 12px 16px; margin-bottom: 20px; border: 1px solid #fecaca; border-radius: 6px; font-size: 0.875rem;">
            <ul style="margin: 0; padding-left: 18px;">
                @foreach($errors->all() as $erro)
                    <li>{{ $erro }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- FORMULÁRIO --}}
    <form action="{{ route('admin.setores.store') }}" method="POST">
        @csrf

        <div class="form-grid-setor">

            {{-- Sigla --}}
            <div class="form-group col-span-3">
                <label for="setor_sigla">Sigla *</label>
                <input type="text"
                       id="setor_sigla"
                       name="setor_sigla"
                       value="{{ old('setor_sigla') }}"
                       placeholder="Ex: CORES"
                       required>
            </div>

            {{-- Nome Completo --}}
            <div class="form-group col-span-9">
                <label for="setor_nome">Nome do Setor *</label>
                <input type="text"
                       id="setor_nome"
                       name="setor_nome"
                       value="{{ old('setor_nome') }}"
                       placeholder="Ex: Coordenação de Registros Escolares"
                       required>
            </div>

            {{-- Título do Formulário --}}
            <div class="form-group col-span-12">
                <label for="titulo">Título do Formulário *</label>
                <input type="text"
                       id="titulo"
                       name="titulo"
                       value="{{ old('titulo') }}"
                       placeholder="Ex: Requerimento - Registro Escolar (CORES)"
                       required>
            </div>

            {{-- E-mail --}}
            <div class="form-group col-span-12">
                <label for="email">E-mail Oficial</label>
                <input type="email"
                       id="email"
                       name="email"
                       value="{{ old('email') }}"
                       placeholder="setor@ifba.edu.br">
            </div>

            {{-- Status Ativo --}}
            <div class="form-group col-span-12" style="margin-top: 4px;">
                <label class="card-toggle-ativo">
                    <input type="checkbox"
                           name="ativo"
                           value="1"
                           {{ old('ativo', '1') ? 'checked' : '' }}
                           style="width: 16px; height: 16px; cursor: pointer; accent-color: #059669;">
                    <div>
                        <strong style="display: block; font-size: 0.8125rem; color: #1e293b;">Setor Ativo</strong>
                        <span style="font-size: 0.75rem; color: #64748b;">Disponível para requerimentos de alunos</span>
                    </div>
                </label>
            </div>

            {{-- Botões --}}
            <div class="col-span-12" style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 10px; padding-top: 16px; border-top: 1px solid #f1f5f9;">
                <a href="{{ route('admin.setores.index') }}" class="btn-cancelar">
                    Cancelar
                </a>
                <button type="submit" class="btn-submit-setor">
                    Criar Setor
                </button>
            </div>

        </div>
    </form>
</div>
@endsection
