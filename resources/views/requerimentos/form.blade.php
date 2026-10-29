@extends('layouts.app')

@section('title', 'Novo Requerimento - SDP IFBA')
@section('tag', 'Aluno')

@section('content')
<link rel="stylesheet" href="{{ asset('css/form.css') }}?v={{ filemtime(public_path('css/form.css')) }}">

@if($isVisualizacaoAdmin ?? false)
    <div style="background: #eff6ff; border: 1px solid #bfdbfe; color: #1e40af; padding: 12px 18px; border-radius: 8px; margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap;">
        <div style="display: flex; align-items: center; gap: 10px;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                <circle cx="12" cy="12" r="3"></circle>
            </svg>
            <div>
                <strong>Modo de Pré-visualização (Visão do Aluno):</strong>
                <span style="font-size: 0.85rem; display: block; color: #3b82f6;">Você está visualizando exatamente como o formulário, requerimentos, links e modelos DOCX/PDF aparecem para os estudantes.</span>
            </div>
        </div>
        <a href="{{ route('admin.setores.edit', $modeloChave) }}" style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; background: #fff; border: 1px solid #bfdbfe; border-radius: 6px; font-size: 0.8125rem; font-weight: 600; color: #1d4ed8; text-decoration: none;">
            &larr; Voltar à Edição do Setor
        </a>
    </div>
@endif

<div class="form-box">
    <!-- Overlay de Carregamento -->
    <x-loading-overlay form-id="formRequerimento" :ajax="true" :redirect-url="route('requerimentos.aluno.meusRequerimentos')" />

    <!-- Navegação entre modelos / setores -->
    <div class="setores-nav">
        <strong>Setor:</strong>
        @foreach($modelos as $mod)
            <a href="{{ ($isVisualizacaoAdmin ?? false) ? route('admin.setores.preview-aluno', $mod['id']) : route('requerimentos.aluno.novo', ['setor' => $mod['id']]) }}"
               class="{{ ($modeloChave ?? '') == $mod['id'] ? 'active' : '' }} btn-nav" id="navegacao-btn">
                {{ $mod['setor_sigla'] }}
            </a>
        @endforeach
    </div>

    <!-- Título do Requerimento -->
    <div style="text-align: center; margin-bottom: 20px; font-size: 1.2rem;">
        <h3 style="margin: 0;">{{ $modeloAtivo['titulo'] ?? 'Requerimento Geral' }}</h3>
        <small style="color: #666;">{{ $setorDestino['nome'] ?? 'Setor Responsável' }}</small>
    </div>

    @if (session('sucesso'))
        <div class="sucesso">✓ {{ session('sucesso') }}</div>
    @endif

    @if ($errors->any())
        <div class="erros">
            <strong>Erros:</strong>
            <ul style="margin: 5px 0 0 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

        <form id="formRequerimento" action="{{ route('aluno.enviar-email') }}" method="POST" enctype="multipart/form-data">
            @csrf
        <input type="hidden" name="setor_id" value="{{ $modeloAtivo['id'] ?? '' }}">
        <input type="hidden" name="setor" value="{{ $modeloAtivo['id'] ?? '' }}">

        {{-- Etapa 1: Identificação, Objeto e Justificativa --}}
        @include('requerimentos.partials.dados-requerimento')

        {{-- Etapa 2: Documentos e Anexos --}}
        @include('requerimentos.partials.documentos-anexos')
    </form>

    <script src="{{ asset('js/form.js') }}?v={{ filemtime(public_path('js/form.js')) }}"></script>

    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                mudarPasso(2);
            });
        </script>
    @endif
</div>
@endsection
