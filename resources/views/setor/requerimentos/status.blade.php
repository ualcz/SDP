@extends('layouts.app')

<style>
    .filtro-container {
        background-color: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        padding: 1rem;
        margin-bottom: 1.5rem;
    }

    .filtro-form {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        align-items: flex-end;
    }

    .filtro-grupo {
        display: flex;
        flex-direction: column;
        flex: 1;
        min-width: 180px;
    }

    .filtro-grupo label {
        font-size: 0.875rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.25rem;
    }

    .filtro-grupo input,
    .filtro-grupo select {
        padding: 0.5rem;
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        font-size: 0.875rem;
    }

    .btn-filtrar {
        background-color: #2563eb;
        color: white;
        padding: 0.5rem 1rem;
        border: none;
        border-radius: 0.375rem;
        font-weight: 600;
        cursor: pointer;
        height: 38px;
    }

    .btn-limpar {
        background-color: #9ca3af;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        text-decoration: none;
        font-size: 0.875rem;
        display: inline-flex;
        align-items: center;
        height: 38px;
        box-sizing: border-box;
    }

    /* Manter estilos existentes */
    .grupo-container {
        margin-bottom: 2rem;
        background-color: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        padding: 1rem;
    }

    .grupo-titulo {
        font-size: 1.125rem;
        font-weight: bold;
        color: #1f2937;
        margin-bottom: 0.75rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #e5e7eb;
    }

    .requerimento-header,
    .requerimento-item {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
        align-items: center;
        padding: 0.5rem 0;
    }

    .requerimento-header {
        font-weight: bold;
        background-color: #f9fafb;
        border-bottom: 1px solid #d1d5db;
        padding-left: 0.5rem;
    }

    .requerimento-item {
        border-bottom: 1px solid #f3f4f6;
        padding-left: 0.5rem;
    }
</style>

@section('content')
<x-btn-voltar />

<h1 style="text-align: center; font-weight: bold; margin-bottom: 1.5rem;">
    {{ $setor->setor_sigla }} - {{ $statusAtual }}
</h1>

{{-- FORMULÁRIO DE FILTRO --}}
<div class="filtro-container">
    <form method="GET" action="{{ url()->current() }}" class="filtro-form">

        <div class="filtro-grupo">
            <label for="busca">Buscar</label>
            <input type="text" name="busca" id="busca" placeholder="Protocolo, Nome ou Matrícula..." value="{{ request('busca') }}">
        </div>
        <div class="filtro-grupo">
            <label for="data_inicio">Data Inicial</label>
            <input type="date" name="data_inicio" id="data_inicio" value="{{ request('data_inicio') }}">
        </div>
        <div class="filtro-grupo">
            <label for="data_fim">Data Final</label>
            <input type="date" name="data_fim" id="data_fim" value="{{ request('data_fim') }}">
        </div>
        <button type="submit" class="btn-filtrar">Filtrar</button>

        @if(request()->hasAny(['busca', 'data_inicio', 'data_fim']))
            <a href="{{ url()->current() }}" class="btn-limpar">Limpar Filtros</a>
        @endif
    </form>
</div>

@forelse ($requerimentosAgrupados as $tipoRequerimento => $itens)
    <div class="grupo-container">
        <h2 class="grupo-titulo">{{ $tipoRequerimento }} - (total: {{ $itens->count() }})</h2>

        <div class="requerimento-header">
            <span>Nº Protocolo</span>
            <span>Nome</span>
            <span>Matrícula</span>
            <span>Data e Hora</span>
            <span>Ações</span>
        </div>

        @foreach ($itens as $requerimento)
            <div class="requerimento-item">
                <p>{{ $requerimento->numero_protocolo }}</p>
                <p>{{ $requerimento->usuario->nome ?? $requerimento->user->name }}</p>
                <p>{{ $requerimento->usuario->matricula ?? $requerimento->user->matricula }}</p>
                <p>{{ $requerimento->created_at->format('d/m/Y H:i') }}</p>
                <p>Ação</p>
            </div>
        @endforeach
    </div>
@empty
    <p style="text-align: center; color: #6b7280;">Nenhum requerimento encontrado para os critérios informados.</p>
@endforelse

@endsection
