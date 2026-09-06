<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Requerimento - {{ $aluno->nome }}</title>
</head>
<body>

    @php
        $modelos = config('modelos_requerimentos.modelos', []);
        $modeloAtivo = null;

        if (!empty($setorChave)) {
            foreach ($modelos as $chave => $mod) {
                if (($mod['setor_chave'] ?? '') === $setorChave || $chave === $setorChave) {
                    $modeloAtivo = $mod;
                    break;
                }
            }
        }

        if (!$modeloAtivo) {
            $modeloAtivo = $modelos['cores'] ?? reset($modelos) ?? [];
        }

        $setorNomeOficial = $modeloAtivo['setor_nome'] ?? $setorNome ?? 'Setor Responsável';
        $listaObjetos = $modeloAtivo['objetos'] ?? [];
        $observacoes = $modeloAtivo['observacoes'] ?? [];
        $objSelecionado = trim($objeto ?? '');
    @endphp

    <h2>INSTITUTO FEDERAL DE EDUCAÇÃO, CIÊNCIA E TECNOLOGIA DA BAHIA</h2>
    <h3>CAMPUS SEABRA</h3>
    <h4>{{ strtoupper($setorNomeOficial) }}</h4>

    <hr>

    <p><strong>REQUERIMENTO - {{ date('Y') }}</strong></p>
    <p><strong>Data da Solicitação:</strong> {{ date('d/m/Y H:i') }}</p>

    <hr>

    <h4>IDENTIFICAÇÃO DO REQUERENTE</h4>
    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <td><strong>Nome:</strong></td>
            <td>{{ $aluno->nome }}</td>
        </tr>
        <tr>
            <td><strong>Matrícula (SUAP):</strong></td>
            <td>{{ $aluno->matricula ?? 'Não informada' }}</td>
        </tr>
        <tr>
            <td><strong>CPF:</strong></td>
            <td>{{ $aluno->cpf ?? 'Não informado' }}</td>
        </tr>
        <tr>
            <td><strong>E-mail:</strong></td>
            <td>{{ $aluno->email_pessoal ?? $aluno->email }}</td>
        </tr>
        <tr>
            <td><strong>Turma / Curso:</strong></td>
            <td>{{ $aluno->turma_codigo ?? 'Não informada' }}</td>
        </tr>
        <tr>
            <td><strong>Endereço:</strong></td>
            <td>{{ $aluno->endereco ?? 'Não informado' }}</td>
        </tr>
        <tr>
            <td><strong>Setor de Destino:</strong></td>
            <td>{{ $setorNomeOficial }}</td>
        </tr>
    </table>

    <hr>

    <h4>OBJETO DO REQUERIMENTO</h4>
    <p><strong>Selecionado:</strong> {{ $objSelecionado ?: 'Não informado' }}</p>

    @if(!empty($listaObjetos))
        <p><strong>Opções do setor:</strong></p>
        <ul>
            @foreach($listaObjetos as $cod => $descricao)
                <li>
                    @if($objSelecionado === $descricao || stripos($objSelecionado, trim(preg_replace('/\s*\(.*?\).*/', '', $descricao))) !== false)
                        <strong>[X] {{ $descricao }}</strong>
                    @else
                        [ ] {{ $descricao }}
                    @endif
                </li>
            @endforeach
        </ul>
    @endif

    @if(!empty($observacoes))
        <p><small>{{ implode(' | ', $observacoes) }}</small></p>
    @endif

    <hr>

    <h4>EXPOSIÇÃO DE MOTIVOS</h4>
    <p>
        @if(!empty($mensagem))
            {!! nl2br(e($mensagem)) !!}
        @else
            <em>Nenhuma observação adicional informada.</em>
        @endif
    </p>

    <hr>

    <p><small>Documento gerado automaticamente pelo SDP - IFBA Campus Seabra | {{ date('d/m/Y H:i:s') }}</small></p>

</body>
</html>
