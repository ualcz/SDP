<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Informações do Aluno</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.5; color: #333;">
    <h2>Informações do Aluno</h2>
    <p>Este e-mail contém os dados cadastrais do aluno encaminhados via SDP (Sistema de Protocolos).</p>

    <p><strong>Setor Selecionado:</strong> {{ $setorNome }}</p>

    <table border="1" cellpadding="6" cellspacing="0" style="border-collapse: collapse; width: 100%; max-width: 600px;">
        <tr style="background-color: #f0f0f0;">
            <th align="left">Campo</th>
            <th align="left">Informação</th>
        </tr>
        <tr>
            <td><strong>Nome</strong></td>
            <td>{{ $aluno->nome }}</td>
        </tr>
        <tr>
            <td><strong>Matrícula (SUAP)</strong></td>
            <td>{{ $aluno->matricula ?? 'Não informada' }}</td>
        </tr>
        <tr>
            <td><strong>CPF</strong></td>
            <td>{{ $aluno->cpf ?? 'Não informado' }}</td>
        </tr>
        <tr>
            <td><strong>E-mail Institucional</strong></td>
            <td>{{ $aluno->email }}</td>
        </tr>
        <tr>
            <td><strong>E-mail Pessoal</strong></td>
            <td>{{ $aluno->email_pessoal ?? 'Não informado' }}</td>
        </tr>
        <tr>
            <td><strong>Turma / Curso</strong></td>
            <td>{{ $aluno->turma_codigo ?? 'Não informada' }}</td>
        </tr>
        <tr>
            <td><strong>Endereço</strong></td>
            <td>{{ $aluno->endereco ?? 'Não informado' }}</td>
        </tr>
    </table>

    @if(!empty($mensagem))
        <h3>Mensagem / Observações do Aluno:</h3>
        <p style="background-color: #f9f9f9; padding: 10px; border-left: 3px solid #0056b3;">
            {!! nl2br(e($mensagem)) !!}
        </p>
    @endif

    <hr style="margin-top: 25px; border: 0; border-top: 1px solid #ccc;">
    <p style="font-size: 11px; color: #666;">
        Enviado automaticamente pelo Sistema de Protocolos (SDP) - IFBA Seabra.
    </p>
</body>
</html>
