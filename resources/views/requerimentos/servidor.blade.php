@extends('layouts.app')

@section('title', 'Painel do Servidor - SDP')
@section('tag', 'Servidor')

@section('content')
<div class="banner servidor">
    <h2>Olá, {{ auth()->user()->nome }}!</h2>
    <p>Painel do Servidor - Sistema de Protocolos do IFBA Seabra.</p>
</div>

<div class="card">
    <h3>Seus Dados</h3>
    
    <div class="grid">
        <div class="item">
            <div class="item-label">Nome Completo</div>
            <div class="item-value">{{ auth()->user()->nome }}</div>
        </div>

        <div class="item">
            <div class="item-label">Matrícula SIAPE / SUAP</div>
            <div class="item-value">{{ auth()->user()->matricula ?? 'N/A' }}</div>
        </div>

        <div class="item">
            <div class="item-label">E-mail Institucional</div>
            <div class="item-value">{{ auth()->user()->email }}</div>
        </div>

        <div class="item">
            <div class="item-label">E-mail Pessoal / Contato</div>
            <div class="item-value">{{ auth()->user()->email_pessoal ?? 'Não cadastrado' }}</div>
        </div>

        <div class="item">
            <div class="item-label">Vínculo Institucional</div>
            <div class="item-value"><span class="badge">Servidor ({{ ucfirst(auth()->user()->role) }})</span></div>
        </div>

        <div class="item">
            <div class="item-label">Autenticação</div>
            <div class="item-value">SUAP Integrado</div>
        </div>
    </div>
</div>
@endsection
