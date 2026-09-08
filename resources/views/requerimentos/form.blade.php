@extends('layouts.app')

@section('title', 'Novo Requerimento - SDP IFBA')
@section('tag', 'Aluno')

@section('content')
<link rel="stylesheet" href="{{ asset('css/form.css') }}">

<div class="form-box">

    <!-- Navegação entre modelos / setores -->
    <div class="setores-nav">
        <strong>Setor:</strong>
        @foreach($modelos as $chave => $mod)
            <a href="{{ route('requerimentos.aluno.novo', ['modelo' => $chave]) }}"
               class="{{ ($modeloChave ?? '') === $chave ? 'active' : '' }}">
                [{{ $mod['setor_sigla'] ?? strtoupper($chave) }}]
            </a>
        @endforeach
    </div>

    <!-- Título do Requerimento -->
    <div style="text-align: center; margin-bottom: 20px;">
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
        <input type="hidden" name="setor" value="{{ $setorChave }}">

        <!-- 1. Identificação do Aluno -->
        <fieldset>
            <legend>Identificação do Aluno</legend>
            <div class="form-linha">
                <div class="campo">
                    <label>Nome:</label>
                    <input type="text" value="{{ auth()->user()->nome }}" readonly>
                </div>
                <div class="campo">
                    <label>Matrícula:</label>
                    <input type="text" value="{{ auth()->user()->matricula ?? '' }}" readonly>
                </div>
                <div class="campo">
                    <label>Turma / Curso:</label>
                    <input type="text" value="{{ auth()->user()->turma_codigo ?? '' }}" readonly>
                </div>
            </div>

            <div class="form-linha">
                <div class="campo">
                    <label>E-mail Pessoal (editável):</label>
                    <input type="email" name="email_pessoal" value="{{ old('email_pessoal', auth()->user()->email_pessoal ?? '') }}" placeholder="seu.email@exemplo.com">
                </div>
                <div class="campo">
                    <label>Telefone / WhatsApp (editável):</label>
                    <input type="text" name="telefone" value="{{ old('telefone', auth()->user()->telefone ?? '') }}" placeholder="(XX) XXXXX-XXXX">
                </div>
            </div>

            <div class="form-linha">
                <div class="campo" style="flex: 2; min-width: 200px;">
                    <label>Rua / Logradouro (editável):</label>
                    <input type="text" name="rua" value="{{ old('rua', auth()->user()->endereco?->rua ?? '') }}" placeholder="Rua / Avenida">
                </div>
                <div class="campo" style="flex: 1; min-width: 90px;">
                    <label>Número:</label>
                    <input type="text" name="numero" value="{{ old('numero', auth()->user()->endereco?->numero ?? '') }}" placeholder="Ex: 60 ou S/N">
                </div>
                <div class="campo" style="flex: 1.5; min-width: 150px;">
                    <label>Bairro:</label>
                    <input type="text" name="bairro" value="{{ old('bairro', auth()->user()->endereco?->bairro ?? '') }}" placeholder="Bairro">
                </div>
            </div>

            <div class="form-linha">
                <div class="campo" style="flex: 2; min-width: 180px;">
                    <label>Cidade:</label>
                    <input type="text" name="cidade" value="{{ old('cidade', auth()->user()->endereco?->cidade ?? '') }}" placeholder="Cidade">
                </div>
                <div class="campo" style="flex: 0.8; min-width: 80px;">
                    <label>Estado (UF):</label>
                    <input type="text" name="estado" maxlength="2" value="{{ old('estado', auth()->user()->endereco?->estado ?? '') }}" placeholder="BA" style="text-transform: uppercase;">
                </div>
                <div class="campo" style="flex: 1.2; min-width: 130px;">
                    <label>CEP:</label>
                    <input type="text" name="cep" value="{{ old('cep', auth()->user()->endereco?->cep ?? '') }}" placeholder="00000-000">
                </div>
            </div>
        </fieldset>

        <!-- 2. Objeto do Requerimento -->
        <fieldset>
            <legend>Objeto do Requerimento</legend>
            <div class="opcoes-objeto">
                @foreach($modeloAtivo['objetos'] ?? [] as $codigo => $descricao)
                    <label>
                        <input type="radio" name="objetoDoRequerimento" value="{{ $descricao }}" {{ $loop->first ? 'checked' : '' }}>
                        {{ $descricao }}
                    </label>
                @endforeach
            </div>

            <div class="campo" style="margin-top: 10px;">
                <label>Outro / Detalhe adicional (opcional):</label>
                <input type="text" name="objeto_outro" placeholder="Especifique caso necessário">
            </div>
        </fieldset>

        <!-- 3. Mensagem / Motivo -->
        <fieldset>
            <legend>Justificativa / Motivo</legend>
            <div class="campo">
                <textarea name="motivo" rows="4" placeholder="Descreva os motivos da sua solicitação..."></textarea>
            </div>
        </fieldset>

        <!-- 4. Anexos -->
        <fieldset>
            <legend>Anexos (opcional)</legend>
            <div class="campo">
                <input type="file" name="arquivos[]" multiple>
            </div>
        </fieldset>

        <!-- Botão de Envio -->
        <button type="submit" class="btn-enviar">
            Enviar Requerimento
        </button>
    </form>

</div>
@endsection
