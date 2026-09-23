@extends('layouts.app')

@section('title', $setor->setor_sigla.' - SDP')
@section('tag', 'Administração')

<link rel="stylesheet" href="{{ asset('css/setorDashboard.css') }}">

<style>
    .btn-editar-setor {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 16px;
        border: 1px solid #1d4ed8;
        border-radius: 8px;
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        box-shadow: 0 4px 10px rgba(37, 99, 235, 0.22);
        color: #ffffff;
        font-size: 0.875rem;
        font-weight: 700;
        text-decoration: none;
        transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
    }

    .btn-editar-setor:hover {
        background: linear-gradient(135deg, #1d4ed8, #1e40af);
        box-shadow: 0 6px 14px rgba(37, 99, 235, 0.3);
        color: #ffffff;
        transform: translateY(-1px);
    }

    .btn-editar-setor:focus-visible {
        outline: 3px solid rgba(37, 99, 235, 0.3);
        outline-offset: 2px;
    }
</style>

@section('content')
<x-btn-voltar />

<div style="display: flex; justify-content: flex-end; margin-bottom: 16px;">
    <a href="{{ route('admin.setores.edit', $setor->id) }}" class="btn-editar-setor">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <path d="M12 20h9"></path>
            <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
        </svg>
        Editar setor
    </a>
</div>

<div class="cards" >
    <a href="{{ route('setor.requerimento.status', ['id' => $setor->id, 'status' => 'aberto']) }}"
        class="card_individual total" style="border-left: 4px solid #D97706">
        <div class="linha1">Requerimentos em aberto</div>
        <div class="linha2">
            <p class="numero">{{ $totalAberto }}</p>
        </div>
        <div class="linha3">
            <p class="info">Aguardando atendimento</p>
        </div>
        <div class="coluna_mesclada">
            <div class="icone_analise" style="background: #D97706;">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="32" height="32" fill="currentColor">
                    <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/>
                </svg>
            </div>
        </div>
    </a>

    <a href="{{ route('setor.requerimento.status', ['id' => $setor->id, 'status' => 'analise']) }}"
        class="card_individual total" style="border-left: 4px solid #7C3AED">
        <div class="linha1">Requerimentos em análise</div>
        <div class="linha2">
            <p class="numero">{{ $totalAnalise }}</p>
        </div>
        <div class="linha3">
            <p class="info">Em andamento pelo setor</p>
        </div>
        <div class="coluna_mesclada">
            <div class="icone_analise" style="background: #7C3AED;">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="32" height="32" fill="currentColor">
                    <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
                </svg>
            </div>
        </div>
    </a>

    <a href="{{ route('setor.requerimento.status', ['id' => $setor->id, 'status' => 'indeferido']) }}"
        class="card_individual total" style="border-left: 4px solid #d8d80a">
        <div class="linha1">Requerimentos Indeferidos</div>
        <div class="linha2">
            <p class="numero">{{ $totalIndeferidos }}</p>
        </div>
        <div class="linha3">
            <p class="info">Aguardando retorno do usuário</p>
        </div>
        <div class="coluna_mesclada">
            <div class="icone_analise" style="background: #d8d80a;">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="32" height="32" fill="currentColor">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 14h-2v-2h2v2zm0-4h-2V7h2v5z"/>
                </svg>
            </div>
        </div>
    </a>
    <a href="{{ route('setor.requerimento.status', ['id' => $setor->id, 'status' => 'concluido']) }}"
        class="card_individual total" style="border-left: 4px solid #3ca13c">
        <div class="linha1">Requerimentos Concluídos</div>
        <div class="linha2">
            <p class="numero">{{ $totalConcluidos }}</p>
        </div>
        <div class="linha3">
            <p class="info">Finalizados</p>
        </div>
        <div class="coluna_mesclada">
            <div class="icone_analise" style="background: #3ca13c;">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="32" height="32" fill="currentColor">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                </svg>
            </div>
        </div>
    </a>

</div>
@endsection
