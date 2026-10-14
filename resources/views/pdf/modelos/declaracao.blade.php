<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Declaração de Ciência - {{ $nome }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 14pt;
            line-height: 1.6;
            color: #000;
            margin: 0px 20px 20px 40px;
        }
        .cabecalho {
            text-align: center;
            margin-bottom: 40px;
        }
        .titulo {
            text-align: center;
            font-weight: bold;
            font-size: 12pt;
            margin-top: 80px;
            margin-bottom: 30px;
            text-transform: uppercase;
        }
        .corpo {
            font-size: 12pt;
            text-align: justify;
            text-indent: 40px; /* Parágrafo padrão */
            margin-bottom: 30px;
        }
        .data-local {
            text-align: center;
            margin-top: 50px;
            margin-bottom: 80px;
        }
        .assinatura {
            text-align: center;
            width: 60%;
            margin: 0 auto;
            border-top: 1px solid #000;
            padding-top: 5px;
            font-size: 12pt;
        }

        .logo {
            height: 120px;
            width: auto;
        }

        .instituto {
            font-weight: bold;
            font-size: 14pt;
        }
    </style>
</head>
<body>
    <table class="topo">
        <tr>
            <td class="cabecalho">
                <img class="logo" src="{{ public_path('img/logo-ifba.png') }}" alt="IFBA">
                <div class="instituto">
                    INSTITUTO FEDERAL DE EDUCAÇÃO, CIÊNCIA E TECNOLOGIA DA BAHIA - CAMPUS SEABRA
                </div>
            </td>
        </tr>
    </table>

    <div class="titulo">DECLARAÇÃO DE CIÊNCIA - Transferência</div>

    <div class="secao">
    <div class="corpo">
        <p>
            Eu, <strong>{{ $nome }}</strong>, inscrito no <strong>CPF nº {{ $cpf }}</strong>,
            aluno(a) do curso <strong>{{ $curso }}</strong>, do Instituto Federal da Bahia - Campus Seabra, matrícula <strong>nº {{$matricula}}</strong>

        </p>
    </div>
    </div>
</body>
</html>
