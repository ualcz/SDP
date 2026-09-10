@extends('layouts.app')

@section('title', 'Painel do Aluno - SDP')
@section('tag', 'Aluno')

@section('content')
<link rel="stylesheet" href="{{ asset('css/home.css') }}">

{{-- Banner de Boas-Vindas --}}
<div class="banner">
    <h2>Olá, {{ auth()->user()->nome }}!</h2>
    <p class="banner-boasvindas">Seja bem-vindo(a) ao Sistema de Protocolos e Requerimentos do IFBA Seabra.</p>
    <p class="banner-aviso">Antes de iniciar um novo requerimento, por favor, verifique se seus dados abaixo estão corretos.</p>
</div>

@if (session('sucesso'))
    <div class="alert alert-success">
        <span>{{ session('sucesso') }}</span>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">
    <div class="card-header">
        <h3>Seus Dados Cadastrais</h3>
        <small style="margin-bottom: 10px; display: block">Mantenha suas informações sempre atualizadas no suap.</small>
    </div>

    <div class="card-body">

        {{-- Seção 1: Identificação --}}
        <div class="section-title">
            Identificação e Curso
        </div>
        <div class="grid">
            <div class="item">
                <div class="item-label">Nome Completo</div>
                <div class="item-value">{{ auth()->user()->nome ?? 'N/A' }}</div>
            </div>
            <div class="item">
                <div class="item-label">CPF</div>
                <div class="item-value">{{ auth()->user()->cpf ?? 'Não identificado' }}</div>
            </div>
            <div class="item">
                <div class="item-label">Matrícula (SUAP)</div>
                <div class="item-value">{{ auth()->user()->matricula ?? 'N/A' }}</div>
            </div>
            <div class="item">
                <div class="item-label">Turma / Curso</div>
                <div class="item-value">{{ auth()->user()->turma_codigo ?? 'Não identificada' }}</div>
            </div>
        </div>

        {{-- Seção 2: Contato --}}
        <div class="section-title">
             Contato
        </div>
        <div class="grid">
            <div class="item">
                <div class="item-label">E-mail Institucional</div>
                <div class="item-value">{{ auth()->user()->email }}</div>
            </div>
            <div class="item">
                <div class="item-label">E-mail Pessoal</div>
                <div class="item-value">{{ auth()->user()->email_pessoal ?? 'Não identificado' }}</div>
            </div>
        </div>

        {{-- Seção 3: Endereço --}}
        <div class="section-title">
             Endereço
        </div>
        <div class="grid">
            <div class="item item-wide">
                {{-- <div class="item-label">Rua</div> --}}
                <div class="item-value">{{ auth()->user()->endereco ?? 'N/A' }}</div>
            </div>

        </div>

    </div>
</div>
@endsection
