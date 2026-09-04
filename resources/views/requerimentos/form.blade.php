@extends('layouts.app')

@section('title', 'Novo Requerimento')
@section('tag', 'Aluno')

@section('content')
<style>
    form {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    label {
        font-weight: bold;
    }

    input, textarea, select {
        padding: 0.5rem;
        border: 1px solid #ccc;
        border-radius: 4px;
    }
</style>

<h1 class="text-2xl font-bold mb-4 text-center">Novo Requerimento</h1>

@if (session('sucesso'))
    <div>
        {{ session('sucesso') }}
    </div>
@endif

@if ($errors->any())
    <div>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('aluno.enviar-email') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div>
        <label for="nome">Nome Completo</label>
        <input type="text" id="nome" name="nome" required value="{{ auth()->user()->nome }}" readonly>
    </div>

    <div>
        <label for="matricula">Matrícula (SUAP):</label>
        <input type="text" id="matricula" name="matricula" value="{{ auth()->user()->matricula ?? 'N/A' }}" readonly>
    </div>

    <div>
        <label for="email">E-mail Institucional:</label>
        <input type="email" id="email" name="email" value="{{ auth()->user()->email }}" readonly>
    </div>

    <div>
        <label for="email_pessoal">E-mail Pessoal:</label>
        <input type="email" id="email_pessoal" name="email_pessoal" value="{{ auth()->user()->email_pessoal ?? '' }}" readonly>
    </div>

    <div>
        <label for="turma_codigo">Turma / Curso:</label>
        <input type="text" id="turma_codigo" name="turma_codigo" value="{{ auth()->user()->turma_codigo ?? '' }}" readonly>
    </div>

    <div>
        <label for="cpf">CPF:</label>
        <input type="text" id="cpf" name="cpf" value="{{ auth()->user()->cpf ?? '' }}" readonly>
    </div>

    <div>
        <label for="endereco">Endereço:</label>
        <input type="text" id="endereco" name="endereco" value="{{ auth()->user()->endereco ?? '' }}" readonly>
    </div>

    <div>
        <label for="setor">Selecione o Setor de Destino:</label>
        <select name="setor" id="setor" required>
            <option value="">-- Escolha o Setor --</option>
            @foreach ($setores ?? config('setores.destinatarios', []) as $chave => $infoSetor)
                <option value="{{ $chave }}">
                    {{ $infoSetor['nome'] ?? $chave }} ({{ is_array($infoSetor['email'] ?? null) ? implode(', ', $infoSetor['email']) : ($infoSetor['email'] ?? '') }})
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="mensagem">Mensagem / Observações (Opcional):</label>
        <textarea name="mensagem" id="mensagem" rows="4" placeholder="Escreva aqui alguma observação ou motivo da solicitação..."></textarea>
    </div>

    <div>
        <label for="arquivos">Anexar Arquivos (Opcional - Selecione um ou mais):</label>
        <input type="file" name="arquivos[]" id="arquivos" multiple>
        <small>Permite múltiplos arquivos (PDF, imagens, documentos, etc.)</small>
    </div>

    <div>
        <button type="submit">Enviar Requerimento</button>
    </div>
</form>
@endsection
