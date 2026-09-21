@extends('layouts.app')

<style>
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

    .btn-voltar {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1rem;
        text-decoration: none;
        color: #374151;
        font-weight: 500;
    }
</style>

@section('content')
<a href="javascript:history.back()" class="btn-voltar">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
         stroke="currentColor" stroke-width="2" stroke-linecap="round"
         stroke-linejoin="round">
        <path d="M19 12H5"/>
        <path d="M12 19l-7-7 7-7"/>
    </svg>
    Voltar
</a>

<h1 style="text-align: center; font-weight: bold;">{{ $setor->setor_sigla }} - Em Análise</h1>
<br>

{{-- Laço Principal: Percorre cada grupo pelo nome do objeto --}}
@forelse ($requerimentosAgrupados as $tipoRequerimento => $itens)
    <div class="grupo-container">
        {{-- Título do Grupo (Objeto do Requerimento) --}}
        <h2 class="grupo-titulo">{{ $tipoRequerimento }} - (total: {{ $itens->count() }})</h2>

        {{-- Cabeçalho da Tabela para o grupo atual --}}
        <div class="requerimento-header">
            <span>Nº Protocolo</span>
            <span>Nome</span>
            <span>Matrícula</span>
            <span>Data e Hora</span>
            <span>Ações</span>
        </div>

        {{-- Segundo Laço: Percorre os requerimentos do grupo atual --}}
        @foreach ($itens as $requerimento)
        <div class="requerimento-item">
                <p>{{  $requerimento->numero_protocolo}}</p>
                <p>{{  $requerimento->usuario->nome }}</p>
                <p>{{  $requerimento->usuario->matricula }}</p>
                <p>{{ $requerimento->created_at->format('d/m/Y H:i') }}</p>
                <a href="">Ver Mais</a>
            </div>
        @endforeach
    </div>
@empty
    <p style="text-align: center; color: #6b7280;">Nenhum requerimento em análise para este setor.</p>
@endforelse

@endsection
