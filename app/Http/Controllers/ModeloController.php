<?php


namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Requerimento;
use App\Models\Setor;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as DomPDF;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ModeloController extends Controller
{
    public function gerarModelo( Request $request)
    {
        // Pega o usuário logado atualmente (o aluno)
        $aluno = auth()->user();

        // Dados que serão injetados na view do PDF
        $dados = [
            'nome' => $aluno->nome,
            'cpf' => $aluno->cpf,
            'matricula' => $aluno->matricula ?? $aluno->turma_codigo, // Ajuste conforme a coluna do seu banco
            'curso' => $aluno->curso ?? 'Análise e Desenvolvimento de Sistemas', // Exemplo
            'dataAtual' => now()->format('d/m/Y'),
        ];

        // Carrega uma view Blade específica para o layout da declaração
        $pdf = Pdf::loadView('pdf.modelos.declaracao', $dados);

        // Define o formato da página (A4) e orientação (retrato)
        $pdf->setPaper('a4', 'portrait');

        $nomeArquivo = 'Declaracao_de_Ciencia_' . Str::slug($aluno->nome) . '.pdf';

        // Se passar ?download=1 baixa direto, senão exibe no navegador (stream)
        if ($request->query('download') == '1') {
            return $pdf->download($nomeArquivo);
        }

        return $pdf->stream($nomeArquivo);
    }
}
