@extends('layouts.app')

@section('title', 'Dashboard - SDP')
@section('tag', 'Administração')

<link rel="stylesheet" href="{{ asset('css/setorDashboard.css') }}">

@section('content')
<x-btn-voltar />

<div class="cards">
    <a href="{{ route('setor.requerimento.status', ['id' => $setor->id, 'status' => 'todos']) }}" class="card_individual analise">
        <div class="linha1">Total de requerimentos</div>
        <div class="linha2">{{ $totalRequerimentos }}</div>
        <div class="linha3">
            <p class="info">Em {{ date('Y') }}</p>
        </div>
        <div class="coluna_mesclada">
            <div class="icone_total" style="background: #2563EB;">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="32" height="32" fill="currentColor">
                    <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
                </svg>
            </div>
        </div>
    </a>

    <a href="{{ route('setor.requerimento.status', ['id' => $setor->id, 'status' => 'recebidos-mes']) }}" class="card_individual recebidos">
        <div class="linha1">Requerimentos recebidos</div>
        <div class="linha2">{{ $totalRecebidosMes ?? $totalRecebidos }}</div>
        <div class="linha3">
            <p class="info">Este Mês</p>
        </div>
        <div class="coluna_mesclada">
            <div class="icone_recebidos" style="background: #0891B2;">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="32" height="32" fill="currentColor">
                    <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 11h-4.18c-.41 1.16-1.51 2-2.82 2s-2.41-.84-2.82-2H5V5h14v9z"/>
                </svg>
            </div>
        </div>
    </a>

    <a href="{{ route('setor.requerimento.status', ['id' => $setor->id, 'status' => 'recebidos-semana']) }}" class="card_individual recebidos">
        <div class="linha1">Requerimentos recebidos</div>
        <div class="linha2">{{ $totalRecebidosSemana ?? $totalRecebidos }}</div>
        <div class="linha3">
            <p class="info">Esta semana</p>
        </div>
        <div class="coluna_mesclada">
            <div class="icone_recebidos" style="background: #D97706;">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="32" height="32" fill="currentColor">
                    <path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2z"/>
                </svg>
            </div>
        </div>
    </a>

    <a href="{{ route('setor.requerimento.status', ['id' => $setor->id, 'status' => 'aberto']) }}" class="card_individual total">
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

    <a href="{{ route('setor.requerimento.status', ['id' => $setor->id, 'status' => 'analise']) }}" class="card_individual total">
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

    <a href="{{ route('setor.requerimento.status', ['id' => $setor->id, 'status' => 'concluido']) }}" class="card_individual total">
        <div class="linha1">Requerimentos Concluídos</div>
        <div class="linha2">
            <p class="numero">{{ $totalConcluidos }}</p>
        </div>
        <div class="linha3">
            <p class="info">Finalizados</p>
        </div>
        <div class="coluna_mesclada">
            <div class="icone_analise" style="background: #059669;">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="32" height="32" fill="currentColor">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                </svg>
            </div>
        </div>
    </a>

</div>
@endsection
