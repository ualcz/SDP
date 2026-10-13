@extends('layouts.app')

@section('title', 'Reportar Problema ou Sugestão')
@section('tag', 'Suporte')

@section('content')
<style>
    .report-container {
        max-width: 700px;
        margin: 2rem auto;
        background-color: #ffffff;
        border-radius: 0.75rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        padding: 2.5rem;
    }

    .report-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .report-header h1 {
        font-size: 1.75rem;
        font-weight: 700;
        color: #111827;
        margin-bottom: 0.5rem;
    }

    .report-header p {
        font-size: 0.95rem;
        color: #6b7280;
        line-height: 1.5;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        font-size: 0.875rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        font-size: 0.95rem;
        color: #1f2937;
        transition: border-color 0.2s, box-shadow 0.2s;
        box-sizing: border-box;
    }

    .form-control:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
    }

    textarea.form-control {
        resize: vertical;
        min-height: 120px;
    }

    .file-upload-wrapper {
        border: 2px dashed #d1d5db;
        padding: 1rem;
        border-radius: 0.375rem;
        text-align: center;
        background-color: #f9fafb;
        cursor: pointer;
        transition: border-color 0.2s;
    }

    .file-upload-wrapper:hover {
        border-color: #2563eb;
    }

    .btn-submit {
        background-color: #2563eb;
        color: #ffffff;
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 0.375rem;
        width: 100%;
        font-size: 1rem;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .btn-submit:hover {
        background-color: #1d4ed8;
    }

    .alert-success {
        background-color: #d1fae5;
        border: 1px solid #10b981;
        color: #065f46;
        padding: 1rem;
        border-radius: 0.375rem;
        margin-bottom: 1.5rem;
        font-size: 0.9rem;
    }

    @media (max-width: 640px) {
        .report-container {
            padding: 1.5rem;
        }
    }

    @media (max-width: 480px) {
        .report-header h1 {
            font-size: 1.5rem;
        }

        .report-header p {
            font-size: 0.85rem;
        }

        .file-input {
            font-size: 0.85rem;
        }
    }
</style>

<div class="report-container">
    <x-btn-voltar class="mb-6" />

    <div class="report-header">
        <h1>Central de Suporte e Feedback</h1>
        <p>Encontrou algum erro inesperado no sistema? Tem alguma sugestão de melhoria ou funcionalidade nova? Preencha o formulário abaixo para enviar diretamente aos desenvolvedores.</p>
    </div>

    @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif
    <x-loading-overlay
            form-id="form-report"
            :mensagens="[
                'Preparando envio...',
                'Renderizando Email...',
                'Enviando para os desenvolvedores...',
                'Só mais um instante...',
                'Quase lá...',
                'A pasciencia é uma virtude...',
            ]"
        />

<form action="{{ route('send.report') }}" method="POST" enctype="multipart/form-data" id="form-report">
        @csrf

        <div class="form-group">
            <label for="nome">Seu Nome</label>
            <input type="text" id="nome" name="nome" class="form-control"
                {{ auth()->check() && auth()->user()->nome ? 'readonly' : '' }}
                required
                value="{{ auth()->check() ? (auth()->user()->nome ?? '') : ($aluno->nome ?? '') }}">
        </div>

        <div class="form-group">
            <label for="email">Seu Email</label>
            <input type="email" id="email" name="email" class="form-control"
                {{ auth()->check() && auth()->user()->email ? 'readonly' : '' }}
                required
                value="{{ auth()->check() ? (auth()->user()->email ?? '') : ($aluno->email ?? '') }}">
        </div>

        <div class="form-group">
            <label for="mensagem">Descrição do Problema ou Sugestão</label>
            <textarea id="mensagem" name="mensagem" class="form-control" required placeholder="Descreva com detalhes o que aconteceu, passos para reproduzir o erro ou qual a sua sugestão..."></textarea>
        </div>

        <div class="form-group">
            <label for="img">Anexar Print/Imagem (Opcional)</label>
            <div class="file-upload-wrapper">
                <input type="file" id="img" name="img" accept="image/*" class='file-input'>
            </div>
            <small style="color: #6b7280; font-size: 0.75rem; margin-top: 0.25rem; display: block;">Envie uma captura de tela para facilitar a identificação do erro (Formatos aceitos: JPG, PNG).</small>
        </div>

        <button type="submit" class="btn-submit">Enviar Relatório</button>
    </form>
</div>
@endsection
