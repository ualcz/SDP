<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Requerimento - {{ $nomeRequerente->nome }}</title>
</head>
<body>
    {{-- ====== CABEÇALHO INSTITUCIONAL ====== --}}
    <table style="width: 100%;">
        <tr>
            <td>
                <img src="{{ public_path('img/logoVertical.png') }}" alt="Logo IFBA" style="width: 60px;">
            </td>
            <td>
                <strong>Instituto Federal de Educação, Ciência e Tecnologia da Bahia</strong><br>
                Campus Seabra | Código INEP: 29448743<br>
                Estrada Vicinal para Tenda, Barro Vermelho, CEP 46.900-000, Seabra - BA
            </td>
        </tr>
    </table>

    <hr>

    <p><strong>COMPROVANTE DE PROTOCOLO DE REQUERIMENTO - {{ date('Y') }}</strong></p>
    <p><strong>Data da Solicitação:</strong> {{ isset($nomeRequerente->created_at) && $nomeRequerente->created_at ? $nomeRequerente->created_at->format('d/m/Y H:i') : (isset($nomeRequerente['created_at']) && $nomeRequerente['created_at'] ? \Carbon\Carbon::parse($nomeRequerente['created_at'])->format('d/m/Y H:i') : date('d/m/Y')) }}</p>
    <p><strong>Número do processo:</strong> 23720_ _ _ _ _ _ /2026-_ _</p>

    <hr>

    <h4>IDENTIFICAÇÃO DO REQUERENTE</h4>
    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <td><strong>Nome do requerente:</strong></td>
            <td>{{ $nomeRequerente->nome }}</td>
        </tr>
        <tr>
            <td><strong>Número da turma:</strong></td>
            <td>{{ $nomeRequerente->turma_codigo ?? 'Não informado' }}</td>
        </tr>
    </table>
    <hr>
    <p><small>Documento gerado automaticamente pelo SDP - IFBA Campus Seabra | {{ date('d/m/Y H:i:s') }}</small></p>

</body>
</html>