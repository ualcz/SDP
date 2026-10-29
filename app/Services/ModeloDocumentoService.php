<?php

namespace App\Services;

use App\Models\DocumentoAssunto;
use App\Models\Usuario;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;
use ZipArchive;

class ModeloDocumentoService
{
    public function isDocx(string $path): bool
    {
        $zip = new ZipArchive();
        if ($zip->open($path) !== true) {
            return false;
        }

        $isDocx = $zip->locateName('[Content_Types].xml') !== false
            && $zip->locateName('word/document.xml') !== false;
        $zip->close();

        return $isDocx;
    }

    public function gerarParaAluno(DocumentoAssunto $documento, Usuario $aluno): string
    {
        if (!$documento->arquivo_modelo || !Storage::disk('local')->exists($documento->arquivo_modelo)) {
            abort(404, 'O modelo deste documento não foi encontrado.');
        }

        $modeloPath = Storage::disk('local')->path($documento->arquivo_modelo);
        $origem = new ZipArchive();
        if ($origem->open($modeloPath) !== true) {
            throw new RuntimeException('Não foi possível abrir o modelo DOCX cadastrado.');
        }

        $endereco = $aluno->endereco;
        $valores = [
            'nome' => $aluno->nome,
            'cpf' => $aluno->cpf,
            'matricula' => $aluno->matricula,
            'turma_codigo' => $aluno->turma_codigo,
            'email' => $aluno->email,
            'email_pessoal' => $aluno->email_pessoal,
            'telefone' => $aluno->telefone,
            'data_nascimento' => $aluno->data_nascimento?->format('d/m/Y'),
            'rua' => $endereco?->rua,
            'numero' => $endereco?->numero,
            'bairro' => $endereco?->bairro,
            'cidade' => $endereco?->cidade,
            'estado' => $endereco?->estado,
            'cep' => $endereco?->cep,
            'endereco' => $endereco?->formatado,
            'data' => now()->format('d/m/Y'),
        ];

        $temporario = tempnam(sys_get_temp_dir(), 'modelo-docx-');
        if ($temporario === false) {
            $origem->close();
            throw new RuntimeException('Não foi possível preparar o arquivo personalizado.');
        }

        $destino = new ZipArchive();
        if ($destino->open($temporario, ZipArchive::OVERWRITE) !== true) {
            $origem->close();
            @unlink($temporario);
            throw new RuntimeException('Não foi possível gerar o documento personalizado.');
        }

        for ($indice = 0; $indice < $origem->numFiles; $indice++) {
            $nome = $origem->getNameIndex($indice);
            $conteudo = $origem->getFromIndex($indice);
            if ($nome === false || $conteudo === false) {
                $origem->close();
                $destino->close();
                @unlink($temporario);
                throw new RuntimeException('O modelo DOCX contém uma entrada inválida.');
            }

            if (str_starts_with($nome, 'word/') && str_ends_with($nome, '.xml')) {
                $conteudo = preg_replace_callback(
                    '/<w:t\b[^>]*>.*?<\/w:t>/s',
                    static function (array $match) use ($valores): string {
                        return preg_replace_callback(
                            '/\{\{\s*([a-zA-Z_]+)\s*\}\}/',
                            static function (array $campo) use ($valores): string {
                                $valor = $valores[strtolower($campo[1])] ?? '';
                                $texto = htmlspecialchars((string) $valor, ENT_XML1 | ENT_QUOTES, 'UTF-8');

                                return str_replace($campo[0], $texto, $campo[0]);
                            },
                            $match[0]
                        );
                    },
                    $conteudo
                ) ?? $conteudo;
            }

            if (!$destino->addFromString($nome, $conteudo)) {
                $origem->close();
                $destino->close();
                @unlink($temporario);
                throw new RuntimeException('Não foi possível escrever o documento personalizado.');
            }
        }

        $origem->close();
        $destino->close();

        if ($aluno->isMenorDeIdade()) {
            $this->adicionarAssinaturasResponsaveis($temporario);
        }

        return $temporario;
    }

    private function adicionarAssinaturasResponsaveis(string $caminho): void
    {
        $word = IOFactory::load($caminho, 'Word2007');
        $secoes = $word->getSections();
        $ultimaSecao = $secoes ? end($secoes) : null;

        if (!$ultimaSecao) {
            throw new RuntimeException('O modelo DOCX não possui uma seção para as assinaturas.');
        }

        $ultimaSecao->addTextBreak(2);
        $ultimaSecao->addText('ASSINATURA DO RESPONSÁVEL LEGAL', ['bold' => true]);
        $ultimaSecao->addTextBreak();
        $ultimaSecao->addText('____________________________________________');
        $ultimaSecao->addText('Responsável legal');

        $temporario = tempnam(sys_get_temp_dir(), 'modelo-assinaturas-');
        if ($temporario === false) {
            throw new RuntimeException('Não foi possível preparar o modelo com as assinaturas.');
        }

        try {
            IOFactory::createWriter($word, 'Word2007')->save($temporario);
            if (!copy($temporario, $caminho)) {
                throw new RuntimeException('Não foi possível incluir as assinaturas dos responsáveis no modelo.');
            }
        } finally {
            @unlink($temporario);
        }
    }

    public function gerarPdfParaAluno(DocumentoAssunto $documento, Usuario $aluno): string
    {
        $docx = $this->gerarParaAluno($documento, $aluno);
        $pdf = tempnam(sys_get_temp_dir(), 'modelo-pdf-');

        if ($pdf === false) {
            @unlink($docx);
            throw new RuntimeException('Não foi possível preparar o PDF personalizado.');
        }

        try {
            if (!Settings::setPdfRenderer(
                Settings::PDF_RENDERER_DOMPDF,
                base_path('vendor/dompdf/dompdf')
            )) {
                throw new RuntimeException('Não foi possível configurar o conversor de PDF.');
            }

            $word = IOFactory::load($docx, 'Word2007');
            IOFactory::createWriter($word, 'PDF')->save($pdf);

            if (!str_starts_with((string) file_get_contents($pdf), '%PDF-')) {
                throw new RuntimeException('A conversão do modelo DOCX para PDF não foi concluída.');
            }

            return $pdf;
        } catch (\Throwable $exception) {
            @unlink($pdf);
            throw new RuntimeException('Não foi possível converter o modelo DOCX para PDF.', 0, $exception);
        } finally {
            @unlink($docx);
        }
    }

    public function nomeArquivo(DocumentoAssunto $documento): string
    {
        return Str::slug($documento->nome ?: 'documento') . '-preenchido.pdf';
    }
}
