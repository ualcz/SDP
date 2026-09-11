<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Requerimento;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as DomPDF;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RequerimentoPdfController extends Controller
{
    /**
     * Transforma a view Blade em um documento PDF (instância do DomPDF).
     */
    public static function criarPdf(
        Usuario $aluno,
        string $setorNome,
        ?string $setorChave = null,
        ?string $objeto = null,
        ?string $mensagem = null
    ): DomPDF {
        return Pdf::loadView('pdf.requerimento', [
            'aluno' => $aluno,
            'setorNome' => $setorNome,
            'setorChave' => $setorChave,
            'objeto' => $objeto,
            'mensagem' => $mensagem,
        ])->setPaper('a4', 'portrait');
    }

    public static function criarComprovante(
        Usuario $nomeRequerente,
        string $numeroTurma,
        ?string $objeto = null,
        string $numeroProtocolo,
        ?\Carbon\Carbon $dataSolicitacao = null,
    ): DomPDF {
        return Pdf::loadView('pdf.comprovante', [
            'nomeRequerente' => $nomeRequerente,
            'numeroTurma' => $numeroTurma,
            'objeto' => $objeto,
            'numeroProtocolo' => $numeroProtocolo,
            'dataSolicitacao'=> $dataSolicitacao,
        ])->setPaper('a4', 'portrait');
    }

    /**
     * Rota para visualizar diretamente a Blade (HTML) no navegador.
     */
    public function visualizarBlade(Request $request)
    {
        $dados = $this->resolverDados($request);

        return view('pdf.requerimento', $dados);
    }

    /**
     * Rota para transformar a Blade em PDF e exibir (stream) ou baixar no navegador.
     */
    public function gerarPdf(Request $request)
    {
        $dados = $this->resolverDados($request);

        $pdf = self::criarPdf(
            aluno: $dados['aluno'],
            setorNome: $dados['setorNome'],
            setorChave: $dados['setorChave'],
            objeto: $dados['objeto'],
            mensagem: $dados['mensagem']
        );

        $nomeArquivo = 'Requerimento_' . Str::slug($dados['aluno']->nome) . '_' . date('Ymd_His') . '.pdf';

        if ($request->query('download') == '1') {
            return $pdf->download($nomeArquivo);
        }

        return $pdf->stream($nomeArquivo);
    }

    public function gerarComprovante($id, Request $request)
    {
        $requerimento = Requerimento::with('usuario')->findOrFail($id);

        $dados = [
            'nomeRequerente' => $requerimento->usuario->nome, 
            'numeroTurma'    => $requerimento->usuario->turma_codigo, 
            'objeto'         => $requerimento->objetoDoRequerimento, 
        ];

        $pdf = self::criarComprovante(
            nomeRequerente: $requerimento->usuario, 
            numeroTurma: $requerimento->usuario->turma_codigo,
            objeto: $requerimento->objetoDoRequerimento,
            dataSolicitacao: $requerimento->created_at,
            numeroProtocolo: $requerimento->numero_protocolo
        );

        $nomeArquivo = 'Comprovante_' . Str::slug($requerimento->usuario->nome) . '_' . date('Ymd_His') . '.pdf';

        if ($request->query('download') == '1') {
            return $pdf->download($nomeArquivo);
        }

        return $pdf->stream($nomeArquivo);
    }

    /**
     * Resolve os dados do aluno e parâmetros do formulário a partir da requisição ou dados autenticados.
     */
    private function resolverDados(Request $request): array
    {
        $aluno = auth()->user();

        // 2. Modelo e setor
        $setorChave = $request->query('setor', 'cores');
        $modelos = Setor::obterSetoresFormatados();
        $setores = config('setores.destinatarios', []);

        $modeloAtivo = $modelos[$setorChave] ?? reset($modelos) ?: [];
        $setorNome = $modeloAtivo['setor_nome'] ?? ($setores[$setorChave]['nome'] ?? 'Coordenação de Registro Escolares');

        // 3. Objeto e Mensagem
        $objeto = $request->query('objeto', 'Atestado de Matrícula e/ou Frequência (02)');
        $mensagem = $request->query('mensagem', 'Solicito a emissão do documento para comprovação de matrícula e frequência.');

        return [
            'aluno' => $aluno,
            'setorNome' => $setorNome,
            'setorChave' => $setorChave,
            'objeto' => $objeto,
            'mensagem' => $mensagem,
        ];
    }
}
