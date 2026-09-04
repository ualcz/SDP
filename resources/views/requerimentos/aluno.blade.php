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
            <div class="item-label">Endereço</div>
            <div class="item-value">{{ auth()->user()->endereco ?? 'Não identificado' }}</div>
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

@if (session('sucesso'))
    <div >
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

<div class="card">
    <h3>Enviar Informações por E-mail</h3>
    <p>Os seus dados cadastrais serão enviados para o seu e-mail e para o setor selecionado.</p>

    <form action="{{ route('aluno.enviar-email') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div>
            <label for="setor"><strong>Selecione o Setor de Destino:</strong></label><br>
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
            <label for="mensagem"><strong>Mensagem / Observações (Opcional):</strong></label><br>
            <textarea name="mensagem" id="mensagem" rows="4" placeholder="Escreva aqui alguma observação ou motivo da solicitação..."></textarea>
        </div>

        <div>
            <label for="arquivos"><strong>Anexar Arquivos (Opcional - Selecione um ou mais):</strong></label><br>
            <input type="file" name="arquivos[]" id="arquivos" multiple>
            <small>Permite múltiplos arquivos (PDF, imagens, documentos, etc.)</small>
        </div>

        <div>
            <button type="submit">
                Enviar Informações
            </button>
        </div>
    </form>
</div>
@endsection
