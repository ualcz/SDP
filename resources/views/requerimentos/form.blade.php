@extends('layouts.app')

@section('title', 'Novo Requerimento - SDP IFBA')
@section('tag', 'Aluno')

@section('content')
<link rel="stylesheet" href="{{ asset('css/form.css') }}">

<div class="form-box">

    <!-- Navegação entre modelos / setores -->
    <div class="setores-nav">
        <strong>Setor:</strong>
        @foreach($modelos as $mod)
            <a href="{{ route('requerimentos.aluno.novo', ['setor' => $mod['id']]) }}"
               class="{{ ($modeloChave ?? '') == $mod['id'] ? 'active' : '' }} btn-nav">
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

    <form action="{{ route('aluno.enviar-email') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="setor_id" value="{{ $modeloAtivo['id'] ?? '' }}">
        <input type="hidden" name="setor" value="{{ $modeloAtivo['id'] ?? '' }}">

        {{-- Etapa 1: Identificação, Objeto e Justificativa --}}
        @include('requerimentos.partials.dados-requerimento')

        {{-- Etapa 2: Documentos e Anexos --}}
        @include('requerimentos.partials.documentos-anexos')
    </form>

    <script src="{{ asset('js/form.js') }}"></script>
    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                mudarPasso(2);
            });
        </script>
    @endif
</div>
@endsection
