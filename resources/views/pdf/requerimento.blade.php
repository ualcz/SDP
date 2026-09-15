<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Requerimento - {{ $aluno->nome }}</title>
    <style>
        {!! file_get_contents(public_path('css/requerimento-pdf.css')) !!}
    </style>
</head>
<body>
    @php
        $modelos = \App\Models\Setor::obterSetoresFormatados();
        $modeloAtivo = null;
        if (!empty($setorChave)) {
            if (isset($modelos[$setorChave])) {
                $modeloAtivo = $modelos[$setorChave];
            } else {
                foreach ($modelos as $mod) {
                    if (strcasecmp($mod['setor_sigla'] ?? '', $setorChave) === 0 || ($mod['id'] ?? '') == $setorChave) {
                        $modeloAtivo = $mod;
                        break;
                    }
                }
            }
        }
        $modeloAtivo = $modeloAtivo ?: (reset($modelos) ?: []);
        $setorNomeOficial = $modeloAtivo['setor_nome'] ?? $setorNome ?? 'Setor Responsável';
        $listaObjetos = array_values($modeloAtivo['objetos'] ?? []);
        $colunasObjetos = array_chunk($listaObjetos, (int) ceil(count($listaObjetos) / 2));
        $objSelecionado = trim($objeto ?? '');
        $prefixoProcesso = $modeloAtivo['processo_prefixo'] ?? '23720';
        $emailSetor = $modeloAtivo['rodape_contato'] ?? $modeloAtivo['email'] ?? '';
        $endereco = $aluno->endereco;
        $cidadeUf = $endereco?->cidade ? $endereco->cidade . ($endereco->estado ? ' - ' . $endereco->estado : '') : '';
        $logoPath = public_path('img/logoVertical.png');
        $logoIfba = file_exists($logoPath)
            ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath))
            : '';
    @endphp

    <table class="topo">
        <tr>
            <td class="logo"><img src="{{ $logoIfba }}" alt="IFBA"></td>
            <td class="instituto">
                INSTITUTO FEDERAL DE EDUCAÇÃO, CIÊNCIA E TECNOLOGIA DA BAHIA<br>
                <span class="campus">CAMPUS SEABRA</span><br>
                <span class="setor">{{ strtoupper($setorNomeOficial) }}</span>
            </td>
            <td class="processo">
                <div class="processo-label">Número do Processo</div>
                <div class="numero-boxes">
                    @foreach(str_split($prefixoProcesso) as $digito)<span>{{ $digito }}</span>@endforeach
                    @foreach(range(1, 6) as $i)<span></span>@endforeach
                    <span class="separador">/</span>
                    @foreach(str_split(date('Y')) as $digito)<span>{{ $digito }}</span>@endforeach
                    <span class="separador">-</span><span></span><span></span>
                </div>
            </td>
        </tr>
    </table>

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
            <table class="objeto-grid">
                <tr>
                    @foreach($colunasObjetos as $coluna)
                        <td>
                            @foreach($coluna as $descricao)
                                @php
                                    $baseDescricao = trim(preg_replace('/\s*\(.*?\).*/', '', $descricao));
                                    $selecionado = $objSelecionado === $descricao || ($baseDescricao !== '' && stripos($objSelecionado, $baseDescricao) !== false);
                                @endphp
                                <div class="check {{ $selecionado ? 'marcado' : '' }}">[{{ $selecionado ? 'X' : ' ' }}] {{ $descricao }}</div>
                            @endforeach
                        </td>
                    @endforeach
                </tr>
            </table>
        </div>
    </div>

    <div class="secao">
        <div class="secao-titulo">EXPOSIÇÃO DE MOTIVOS</div>
        <div class="linhas">
            @if(!empty($mensagem))<div style="font-size: 7pt; margin-bottom: 1mm;">{!! nl2br(e($mensagem)) !!}</div>@endif
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
