@extends('layouts.app')

@section('title', ($modeloAtivo['titulo'] ?? 'Requerimento') . ' - SDP IFBA')
@section('tag', 'Aluno')

@section('content')
<div>

    <!-- Seletor de Modelos de Requerimento por Setor -->
    <div>
        <strong>Selecione o Setor / Modelo de Requerimento:</strong><br><br>
        <div>
            @foreach($modelos as $chave => $mod)
                <a href="{{ route('requerimentos.aluno.novo', ['modelo' => $chave]) }}">
                    [{{ $mod['setor_sigla'] ?? strtoupper($chave) }}]
                </a>
                &nbsp;
            @endforeach
        </div>
    </div>
    <br>

    <!-- Cabeçalho Oficial do Setor -->
    <div>
        <h3>INSTITUTO FEDERAL DE EDUCAÇÃO, CIÊNCIA E TECNOLOGIA DA BAHIA</h3>
        <h4>CAMPUS SEABRA</h4>
        <h4>{{ strtoupper($modeloAtivo['setor_nome'] ?? 'COORDENAÇÃO DE REGISTRO ESCOLARES (CORES)') }}</h4>
        <p><strong>REQUERIMENTO Nº {{ date('Y') }}/_____ &nbsp;&nbsp;&nbsp;&nbsp; Processo: {{ $modeloAtivo['processo_prefixo'] ?? '23720' }}.______/{{ date('Y') }}-__</strong></p>
    </div>
    <hr>

    @if (session('sucesso'))
        <div>
            <strong>✓ {{ session('sucesso') }}</strong>
        </div>
        <br>
    @endif

    @if ($errors->any())
        <div>
            <strong>Erros encontrados:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        <br>
    @endif

    <form action="{{ route('aluno.enviar-email') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Setor de Destino vinculado ao modelo ativo -->
        <input type="hidden" name="setor" value="{{ $setorChave }}">

        <!-- 1. IDENTIFICAÇÃO DO REQUERENTE -->
        <fieldset>
            <legend><strong>IDENTIFICAÇÃO DO REQUERENTE</strong></legend>

            <div>
                <label><strong>Nome do Requerente:</strong></label><br>
                <input type="text" name="nome" value="{{ auth()->user()->nome }}" readonly>
            </div>
            <br>

            <div>
                <label><strong>Nº do CPF:</strong></label><br>
                <input type="text" name="cpf" value="{{ auth()->user()->cpf ?? '' }}" readonly>
            </div>
            <br>

            <div>
                <label><strong>Matrícula (SUAP):</strong></label><br>
                <input type="text" name="matricula" value="{{ auth()->user()->matricula ?? '' }}" readonly>
            </div>
            <br>

            <div>
                <label><strong>Nº da Turma / Código:</strong></label><br>
                <input type="text" name="turma_codigo" value="{{ auth()->user()->turma_codigo ?? '' }}" readonly>
            </div>
            <br>

            <div>
                <label><strong>Endereço:</strong></label><br>
                <input type="text" name="endereco" value="{{ auth()->user()->endereco ?? '' }}" readonly>
            </div>
            <br>

            <div>
                <label><strong>E-mail Institucional:</strong></label><br>
                <input type="email" name="email" value="{{ auth()->user()->email }}" readonly>
            </div>
            <br>

            <div>
                <label><strong>E-mail Pessoal:</strong></label><br>
                <input type="email" name="email_pessoal" value="{{ auth()->user()->email_pessoal ?? '' }}" readonly>
            </div>
            <br>

            <div>
                <label><strong>Data da Solicitação:</strong></label><br>
                <input type="text" name="data_solicitacao" value="{{ date('d/m/Y') }}" readonly>
            </div>
            <br>

            <div>
                <label><strong>Setor de Destino:</strong></label><br>
                <input type="text" value="{{ $setorDestino['nome'] ?? $modeloAtivo['setor_nome'] }} ({{ is_array($setorDestino['email'] ?? null) ? implode(', ', $setorDestino['email']) : ($setorDestino['email'] ?? $modeloAtivo['email'] ?? '') }})" readonly>
            </div>
        </fieldset>
        <br>

        <!-- 2. OBJETO DO REQUERIMENTO -->
        <fieldset>
            <legend><strong>OBJETO DO REQUERIMENTO - {{ $modeloAtivo['setor_sigla'] ?? '' }}</strong></legend>

            <div>
                @foreach($modeloAtivo['objetos'] ?? [] as $codigo => $descricao)
                    <div>
                        <label>
                            <input type="radio" name="objeto" value="{{ $descricao }}" {{ $loop->first ? 'checked' : '' }}>
                            {{ $descricao }}
                        </label>
                    </div>
                @endforeach
            </div>
            <br>

            <div>
                <label for="objeto_outro"><strong>Se necessário, especifique detalhes / outro motivo:</strong></label><br>
                <input type="text" name="objeto_outro" id="objeto_outro" placeholder="Especifique caso necessário">
            </div>

            @if(!empty($modeloAtivo['observacoes']))
                <br>
                <div>
                    @foreach($modeloAtivo['observacoes'] as $obs)
                        <small>{{ $obs }}</small><br>
                    @endforeach
                </div>
            @endif
        </fieldset>
        <br>

        <!-- 3. EXPOSIÇÃO DE MOTIVOS -->
        <fieldset>
            <legend><strong>EXPOSIÇÃO DE MOTIVOS</strong></legend>

            <div>
                <textarea name="mensagem" id="mensagem" rows="5" placeholder="Descreva aqui detalhadamente a justificativa ou motivo do seu requerimento..."></textarea>
            </div>
        </fieldset>
        <br>

        <!-- 4. ANEXOS (DOCUMENTOS COMPROBATÓRIOS) -->
        <fieldset>
            <legend><strong>ANEXOS / COMPROVANTES (Opcional)</strong></legend>

            <div>
                <input type="file" name="arquivos[]" id="arquivos" multiple><br>
                <small>Permite anexar comprovantes, atestados, ementas ou documentos comprobatórios (PDF, imagens, etc.).</small>
            </div>
        </fieldset>
        <br>

        <!-- Botão de Envio -->
        <div>
            <button type="submit">
                Enviar Requerimento ({{ $modeloAtivo['setor_sigla'] ?? 'Setor' }})
            </button>
        </div>

    </form>

    <br>
    <hr>
    <div>
        <small>
            IFBA Campus Seabra - Estrada Vicinal para Tenda, Zona Rural, Barro Vermelho. CEP: 46.900.000. Tel: (75) 99811-1125 / 99811-1016<br>
            {{ $modeloAtivo['rodape_contato'] ?? 'Contato: sdp.seabra@ifba.edu.br' }}
        </small>
    </div>

</div>
@endsection
