@extends('layouts.app')

@section('title', 'Modelos de Requerimentos - SDP')
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
    .tabela-modelos {
        border-collapse: collapse;
        width: 100%;
        margin-top: 15px;
    }
    .tabela-modelos th, .tabela-modelos td {
        border: 1px solid #e5e7eb;
        padding: 10px 14px;
        text-align: left;
    }
    .tabela-modelos th {
        background: #f9fafb;
        font-weight: 600;
        color: #374151;
        font-size: 0.875rem;
    }
    .tabela-modelos tr:hover {
        background-color: #f9fafb;
    }
    .badge-ativo {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        background: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
    }
    .badge-inativo {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }
    .btn-acao {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 0.8125rem;
        font-weight: 600;
        text-decoration: none;
        background-color: #2563eb;
        color: #fff;
        transition: background-color 0.2s;
    }
    .btn-acao:hover {
        background-color: #1d4ed8;
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
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
        <div>
            <h2 style="font-size: 1.5rem; font-weight: 700; margin: 0; color: #111827;">Modelos de Requerimentos</h2>
            <p style="color: #6b7280; font-size: 0.875rem; margin: 4px 0 0 0;">Gerencie os modelos dos setores e os assuntos (objetos de requerimento) vinculados a cada um.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn-voltar">
            &larr; Voltar ao Dashboard
        </a>
    </div>

    @if(session('success'))
        <div style="background: #e6f4ea; color: #137333; padding: 12px 16px; margin: 16px 0; border: 1px solid #ceead6; border-radius: 6px;">
            {{ session('success') }}
        </div>
    @endif

    <table class="tabela-modelos">
        <thead>
            <tr>
                <th style="width: 100px;">Sigla</th>
                <th>Setor</th>
                <th>Título do Formulário</th>
                <th>E-mail do Setor</th>
                <th style="width: 170px; text-align: center;">Assuntos (Ativos / Total)</th>
                <th style="width: 100px; text-align: center;">Status</th>
                <th style="width: 180px; text-align: center;">Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse($modelos as $modelo)
                <tr>
                    <td><strong>{{ $modelo->setor_sigla }}</strong></td>
                    <td>{{ $modelo->setor_nome }}</td>
                    <td>{{ $modelo->titulo }}</td>
                    <td>{{ $modelo->email ?: '—' }}</td>
                    <td style="text-align: center;">
                        <span style="font-weight: 600; color: #2563eb;">{{ $modelo->assuntos_ativos_count }}</span>
                        <span style="color: #9ca3af;">/</span>
                        <span>{{ $modelo->assuntos_count }}</span>
                    </td>
                    <td style="text-align: center;">
                        @if($modelo->ativo)
                            <span class="badge-ativo">Ativo</span>
                        @else
                            <span class="badge-inativo">Inativo</span>
                        @endif
                    </td>
                    <td style="text-align: center;">
                        <a href="{{ route('admin.modelos.edit', $modelo->id) }}" class="btn-acao">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Editar / Assuntos
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 24px; color: #6b7280;">Nenhum modelo cadastrado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
