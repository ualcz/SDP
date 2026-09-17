<?php

namespace App\Services\Pdf;

use Illuminate\Support\Facades\Log;
use setasign\Fpdi\Fpdi;

class PdfMergerService
{
    protected PdfImporter $pdfImporter;
    protected ImagePageProcessor $imageProcessor;
    protected AttachmentInspector $inspector;

    public function __construct(
        ?PdfImporter $pdfImporter = null,
        ?ImagePageProcessor $imageProcessor = null,
        ?AttachmentInspector $inspector = null
    ) {
        $this->pdfImporter = $pdfImporter ?? app(PdfImporter::class);
        $this->imageProcessor = $imageProcessor ?? app(ImagePageProcessor::class);
        $this->inspector = $inspector ?? app(AttachmentInspector::class);
    }

    /**
     * Mescla o conteúdo do PDF principal (requerimento) com anexos (PDFs e Imagens)
     * em um único arquivo PDF unificado.
     *
     * @param string $pdfPrincipalConteudo Binário do PDF gerado pelo DomPDF
     * @param array $arquivos Lista de UploadedFile ou caminhos de arquivos anexos
     * @param array &$arquivosNaoMesclados Lista preenchida com arquivos que não puderam ser convertidos/mesclados
     * @return string Binário do PDF resultante com todas as páginas unificadas
     */
    public function mesclarComAnexos(
        string $pdfPrincipalConteudo,
        array $arquivos = [],
        array &$arquivosNaoMesclados = []
    ): string {
        $fpdi = new Fpdi();

        // 1. Importa as páginas do documento principal do Requerimento
        $this->pdfImporter->importFromString($fpdi, $pdfPrincipalConteudo);

        // 2. Itera sobre cada anexo enviado
        foreach ($arquivos as $arquivo) {
            $caminho = $this->inspector->getRealPath($arquivo);
            if (!$caminho || !file_exists($caminho)) {
                continue;
            }

            try {
                if ($this->inspector->isPdf($arquivo, $caminho)) {
                    $this->pdfImporter->importFromFile($fpdi, $caminho);
                } elseif ($this->inspector->isImage($arquivo, $caminho)) {
                    $this->imageProcessor->addImageAsPage($fpdi, $caminho);
                } else {
                    Log::info("PdfMergerService: Arquivo não mesclável mantido avulso: {$caminho}");
                    $arquivosNaoMesclados[] = $arquivo;
                }
            } catch (\Throwable $e) {
                Log::error("PdfMergerService: Erro ao mesclar anexo {$caminho}: " . $e->getMessage());
                $arquivosNaoMesclados[] = $arquivo;
            }
        }

        // 3. Retorna o PDF consolidado como string binária
        return $fpdi->Output('S');
    }
}
