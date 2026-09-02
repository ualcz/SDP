@extends('layouts.app')

@section('title', 'Painel do Aluno - SDP')
@section('tag', 'Aluno')

@section('content')
<div class="banner">
    <h2>Olá, {{ auth()->user()->nome }}!</h2>
    <p>Sistema de Protocolos e Requerimentos do IFBA Seabra.</p>
</div>

<div class="card">
    <h3>Seus Dados</h3>
    
    <div class="grid">
        <div class="item">
            <div class="item-label">Nome Completo</div>
            <div class="item-value">{{ auth()->user()->nome }}</div>
        </div>

        <div class="item">
            <div class="item-label">Matrícula (SUAP)</div>
            <div class="item-value">{{ auth()->user()->matricula ?? 'N/A' }}</div>
        </div>

        <div class="item">
            <div class="item-label">E-mail Institucional</div>
            <div class="item-value">{{ auth()->user()->email }}</div>
        </div>

        <div class="item">
            <div class="item-label">E-mail Pessoal</div>
            <div class="item-value">{{ auth()->user()->email_pessoal ?? 'Não identificado' }}</div>
        </div>

        <div class="item">
            <div class="item-label">Turma / Curso</div>
            <div class="item-value">{{ auth()->user()->turma_codigo ?? 'Não identificada' }}</div>
        </div>
        
        <div class="item">
            <div class="item-label">CPF</div>
            <div class="item-value">{{ auth()->user()->cpf ?? 'Não identificado' }}</div>
        </div>

        <div class="item">
            <div class="item-label">Vínculo</div>
            <div class="item-value"><span class="badge">{{ ucfirst(auth()->user()->role) }}</span></div>
        </div>
    </div>
</div>
@endsection
