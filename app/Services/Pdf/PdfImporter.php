<?php

namespace App\Services\Pdf;

use setasign\Fpdi\Fpdi;

class PdfImporter
{
    /**
     * Importa páginas de um PDF a partir de uma string binária (ex: saída do DomPDF).
     */
    public function importFromString(Fpdi $fpdi, string $pdfContent): void
    {
        $tempPath = tempnam(sys_get_temp_dir(), 'fpdi_import_');
        file_put_contents($tempPath, $pdfContent);

        try {
            $this->importFromFile($fpdi, $tempPath);
        } finally {
            if (file_exists($tempPath)) {
                @unlink($tempPath);
            }
        }
    }

    /**
     * Importa todas as páginas de um arquivo PDF existente preservando tamanho e orientação.
     */
    public function importFromFile(Fpdi $fpdi, string $filePath): void
    {
        $pageCount = $fpdi->setSourceFile($filePath);

        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $templateId = $fpdi->importPage($pageNo);
            $size = $fpdi->getTemplateSize($templateId);

            $orientation = ($size['width'] > $size['height']) ? 'L' : 'P';
            $fpdi->AddPage($orientation, [$size['width'], $size['height']]);
            $fpdi->useTemplate($templateId);
        }
    }
}
