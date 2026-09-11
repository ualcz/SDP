<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Requerimento - {{ $aluno->nome }}</title>
    {{-- O css tava bugando na geração do PDF, então coloquei aqui --}}
    <style>
        @page {
            margin: 7mm 8mm 6mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            color: #111;
            font-family: DejaVu Sans, sans-serif;
            font-size: 7.5pt;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .topo {
            text-align: center;
            width: 100%;
            margin-bottom: 2mm;
        }

        .container-logo {
            text-align: center;
            margin-bottom: 1mm;
        }

        .logo {
            width: 13mm;
            height: 13mm;
            display: inline-block;
        }

        .instituto {
            font-family: Arial, Helvetica, sans-serif;
            text-align: center;
            font-size: 11pt;
            font-weight: bold;
            line-height: 1.25;
        }

        .instituto .campus {
            font-size: 10pt;
        }

        .instituto .setor {
            font-size: 8pt;
            font-family: Arial, Helvetica, sans-serif;
        }

        .titulo {
            margin-top: 1.5mm;
            border: 0.5pt solid #555;
            padding: 1.3mm 2mm;
            font-size: 12pt;
            font-weight: bold;
        }

        .titulo .ano {
            font-size: 10pt;
        }

        .secao {
            margin-top: 2mm;
            border-top: 0.5pt solid #555;
            padding-top: 1mm;
        }

        .secao-titulo {
            text-align: center;
            font-weight: bold;
            font-size: 10pt;
            margin-bottom: 1mm;
        }

        .campos {
            width: 100%;
            table-layout: fixed;
        }

        .campos td {
            border: 0.5pt solid #777;
            height: 7mm;
            padding: 1mm 1.5mm;
            vertical-align: top;
            word-wrap: break-word;
        }

        .campos .rotulo {
            display: block;
            font-size: 7pt;
            font-weight: 700;
        }

        .campos .valor {
            display: block;
            font-size: 9pt;
            margin-top: 1mm;
            min-height: 3mm;
        }

        .objeto {
            border: 0.5pt solid #777;
            padding: 1.5mm 2mm;
        }

        .objeto-grid {
            table-layout: fixed;
        }

        .objeto-grid td {
            width: 50%;
            vertical-align: top;
            padding: 0.5mm 2mm 0.5mm 0;
        }

        .observacoes {
            margin-top: 1.5mm;
            font-size: 9pt;
            font-weight: bold;
            line-height: 1.25;
        }

        .linhas {
            border: 0.5pt solid #777;
            padding: 1.5mm 2mm 0;
            height: 27mm;
        }

        .linha {
            height: 6mm;
            border-bottom: 0.4pt solid #aaa;
        }

        .pareceres {
            margin-top: 2mm;
            table-layout: fixed;
        }

        .pareceres td {
            width: 50%;
            padding-right: 4mm;
            vertical-align: top;
        }

        .parecer-titulo {
            border-bottom: 0.4pt solid #777;
            padding-bottom: 1mm;
            font-weight: bold;
        }

        .parecer-linhas {
            height: 15mm;
            border-bottom: 0.4pt dotted #777;
        }

        .assinatura {
            margin-top: 1mm;
            border-top: 0.5pt solid #555;
            padding-top: 1mm;
            text-align: center;
            font-size: 6.5pt;
            font-weight: bold;
        }
    </style>
</head>
<body>
    @php
        $modelos = \App\Models\Setor::obterSetoresFormatados();
        $modeloAtivo = null;
        foreach ($modelos as $chave => $mod) {
            if (($mod['setor_chave'] ?? '') === $setorChave || $chave === $setorChave) {
                $modeloAtivo = $mod;
                break;
            }
        }
        $modeloAtivo = $modeloAtivo ?: ($modelos['cores'] ?? reset($modelos) ?? []);
        $setorNomeOficial = $modeloAtivo['setor_nome'] ?? $setorNome ?? 'Setor Responsável';
        $listaObjetos = array_values($modeloAtivo['objetos'] ?? []);
        $colunasObjetos = array_chunk($listaObjetos, (int) ceil(count($listaObjetos) / 2));
        $observacoes = $modeloAtivo['observacoes'] ?? [];
        $objSelecionado = trim($objeto ?? '');
        $prefixoProcesso = $modeloAtivo['processo_prefixo'] ?? '23720';
        $emailSetor = $modeloAtivo['rodape_contato'] ?? $modeloAtivo['email'] ?? '';
        $endereco = $aluno->endereco;
        $cidadeUf = $endereco?->cidade ? $endereco->cidade . ($endereco->estado ? ' - ' . $endereco->estado : '') : '';

        $logoPath = base_path('public/img/logoVertical.png');
        $logoIfba = file_exists($logoPath)
            ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath))
            : '';
    @endphp

    <div class="topo">
        <div class="container-logo">
            <img class="logo" src="{{ $logoIfba }}" alt="IFBA">
        </div>
        <div class="instituto">
            INSTITUTO FEDERAL DE EDUCAÇÃO, CIÊNCIA E TECNOLOGIA DA BAHIA<br>
            <span class="campus">CAMPUS SEABRA</span><br>
            <span class="setor">{{ strtoupper($setorNomeOficial) }}</span>
        </div>
    </div>

    <div class="titulo">REQUERIMENTO Nº <span class="ano">{{ date('Y') }}/</span>________________</div>

    <div class="secao">
        <div class="secao-titulo">IDENTIFICAÇÃO DO REQUERENTE</div>
        <table class="campos">
            <colgroup>
                <col style="width: 25%;">
                <col style="width: 25%;">
                <col style="width: 25%;">
                <col style="width: 25%;">
            </colgroup>
            <tr>
                <td colspan="3"><span class="rotulo">Nome do Requerente</span><span class="valor">{{ $aluno->nome }}</span></td>
                <td><span class="rotulo">Nº do CPF</span><span class="valor">{{ $aluno->cpf ?? '' }}</span></td>
            </tr>
            <tr>
                <td><span class="rotulo">Nº da TURMA (para estudante do IFBA)</span><span class="valor">{{ $aluno->turma_codigo ?? '' }}</span></td>
                <td colspan="2"><span class="rotulo">Documento de Identificação (para público externo ao IFBA)</span><span class="valor"></span></td>
                <td><span class="rotulo">Tipo de documentação (especificar)</span><span class="valor"></span></td>
            </tr>
            <tr>
                <td colspan="2"><span class="rotulo">Endereço</span><span class="valor">{{ $endereco?->rua ?? '' }}</span></td>
                <td colspan="2"><span class="rotulo">Cidade</span><span class="valor">{{ $cidadeUf }}</span></td>
            </tr>
            <tr>
                <td><span class="rotulo">Bairro</span><span class="valor">{{ $endereco?->bairro ?? '' }}</span></td>
                <td><span class="rotulo">Telefone</span><span class="valor">{{ $aluno->telefone ?? '' }}</span></td>
                <td><span class="rotulo">E-mail</span><span class="valor">{{ $aluno->email_pessoal ?? $aluno->email }}</span></td>
                <td><span class="rotulo">CEP</span><span class="valor">{{ $endereco?->cep ?? '' }}</span></td>
            </tr>
            <tr>
                <td colspan="2"><span class="rotulo">Curso/Turma</span><span class="valor">{{ $aluno->turma_codigo ?? '' }}</span></td>
                <td><span class="rotulo">Data</span><span class="valor">{{ date('d/m/Y') }}</span></td>
                <td><span class="rotulo">Assinatura</span><span class="valor"></span></td>
            </tr>
        </table>
    </div>

    <div class="secao">
        <div class="secao-titulo">OBJETO DO REQUERIMENTO</div>
        <div class="objeto">
            @php
                $itemExibido = false;
            @endphp

            @foreach($listaObjetos as $descricao)
                @php
                    $baseDescricao = trim(preg_replace('/\s*\(.*?\).*/', '', $descricao));
                    $selecionado = $objSelecionado === $descricao || ($baseDescricao !== '' && stripos($objSelecionado, $baseDescricao) !== false);
                @endphp
                @if($selecionado)
                    <div class="valor" style="font-size: 9pt; font-weight: bold;">{{ $descricao }}</div>
                    @php $itemExibido = true; @endphp
                    @break
                @endif
            @endforeach

            @if(!$itemExibido && !empty($objSelecionado))
                <div class="valor" style="font-size: 9pt; font-weight: bold;">{{ $objSelecionado }}</div>
            @endif

            @if(!empty($observacoes))
                <div class="observacoes">
                    @foreach($observacoes as $observacao)
                        {{ $observacao }}<br>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <div class="secao">
        <div class="secao-titulo">EXPOSIÇÃO DE MOTIVOS</div>
        <div class="linhas">
            @if(!empty($mensagem))<div style="font-size: 9pt;">{!! nl2br(e($mensagem)) !!}</div>@endif
            <div class="linha"></div><div class="linha"></div><div class="linha"></div><div class="linha"></div>
        </div>
    </div>

    <table class="pareceres">
        <tr>
            <td><div class="parecer-titulo">Parecer da Coordenação de Curso/COTEP/Dacad</div><div class="parecer-linhas"></div><div class="assinatura">ASS: ____________________ DATA: ____/____/____</div></td>
            <td><div class="parecer-titulo">Parecer da Biblioteca</div><div class="parecer-linhas"></div><div class="assinatura">ASS: ____________________ DATA: ____/____/____</div></td>
        </tr>
    </table>

</body>
</html>
