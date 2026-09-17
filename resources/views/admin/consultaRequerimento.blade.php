@extends('layouts.app')

@section('title', 'Dashboard - SDP')
@section('tag', 'Administração')

@section('content')
<style>
    .dash-filter-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 14px 18px;
        margin-bottom: 20px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
    }

    .dash-filter-form {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        align-items: center;
    }

    .input-filtro {
        width: 100%;
        padding: 8px 12px;
        font-size: 0.875rem;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        background: #ffffff;
        color: #1e293b;
        box-sizing: border-box;
    }

    .input-filtro:focus {
        outline: none;
        border-color: #059669;
        box-shadow: 0 0 0 2px rgba(5, 150, 105, 0.15);
    }

    .btn-filtrar {
        background-color: #059669;
        color: #ffffff;
        padding: 8px 18px;
        font-size: 0.875rem;
        font-weight: 500;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        transition: background-color 0.15s;
    }

    .btn-filtrar:hover {
        background-color: #047857;
    }

    .btn-limpar {
        background: #f1f5f9;
        color: #475569;
        padding: 8px 14px;
        font-size: 0.875rem;
        border-radius: 6px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        transition: background-color 0.15s;
    }

    .btn-limpar:hover {
        background: #e2e8f0;
        color: #1e293b;
    }

    .dash-section {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 20px 24px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
    }

    .dash-section-title {
        font-size: 1.05rem;
        font-weight: 600;
        color: #1e293b;
        margin: 0 0 16px 0;
    }

    .dash-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.875rem;
    }

    .dash-table th {
        background: #f8fafc;
        color: #475569;
        font-weight: 600;
        text-align: left;
        padding: 10px 14px;
        border-bottom: 1px solid #e2e8f0;
    }

    .dash-table td {
        padding: 12px 14px;
        border-bottom: 1px solid #f1f5f9;
        color: #1e293b;
    }

    .dash-table tbody tr:hover {
        background-color: #f8fafc;
    }

    .badge-setor {
        background: #ecfdf5;
        color: #047857;
        font-weight: 600;
        padding: 3px 8px;
        border-radius: 4px;
        font-size: 0.75rem;
    }
</style>

{{-- Filtro de Pesquisa por Aluno ou Setor --}}
<div class="dash-filter-card">
    <form method="GET" action="{{ route('admin.dashboard') }}" class="dash-filter-form">
        <div style="flex: 2; min-width: 220px;">
            <input type="text" name="aluno" value="{{ request('aluno') }}" placeholder="Buscar por aluno..." class="input-filtro">
        </div>
        <div style="flex: 1; min-width: 180px;">
            <select name="setor" class="input-filtro">
                <option value="">Todos os setores</option>
                @foreach($setores as $s)
                    <option value="{{ $s->id }}" {{ request('setor') == $s->id ? 'selected' : '' }}>
                        {{ $s->setor_sigla }} &mdash; {{ $s->setor_nome }}
                    </option>
                @endforeach
            </select>
        </div>
        <div style="display: flex; gap: 8px;">
            <button type="submit" class="btn-filtrar">
                Buscar
            </button>
            @if(request()->filled('aluno') || request()->filled('setor'))
                <a href="{{ route('admin.dashboard') }}" class="btn-limpar">
                    Limpar
                </a>
            @endif
        </div>
    </form>
</div>

<section class="dash-section">
    <h3 class="dash-section-title">Requerimentos recentes</h3>

    @if($requerimentos->isEmpty())
        <p style="color: #64748b; font-size: 0.875rem; margin: 0; padding: 12px 0;">
            @if(request()->filled('aluno') || request()->filled('setor'))
                Nenhum requerimento encontrado para esta busca.
            @else
                Nenhum requerimento cadastrado.
            @endif
        </p>
    @else
        <div style="overflow-x: auto;">
            <table class="dash-table">
                <thead>
                    <tr>
                        <th>Solicitante</th>
                        <th>Requerimento</th>
                        <th style="width: 120px; text-align: center;">Setor</th>
                        <th style="width: 140px;">Data</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($requerimentos as $requerimento)
                        <tr>
                            <td>{{ $requerimento->usuario?->nome ?? 'Usuário removido' }}</td>
                            <td>{{ $requerimento->objetoDoRequerimento }}</td>
                            <td style="text-align: center;">
                                <span class="badge badge-setor" title="{{ $requerimento->setor_nome }}">
                                    {{ $requerimento->setor?->setor_sigla ?? $requerimento->setor_sigla }}
                                </span>
                            </td>
                            <td style="color: #64748b; font-size: 0.8125rem;">
                                {{ $requerimento->created_at?->format('d/m/Y H:i') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</section>
@endsection