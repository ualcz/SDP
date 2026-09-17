<?php

namespace App\Services\Pdf;

use setasign\Fpdi\Fpdi;

class ImagePageProcessor
{
    /**
     * Resolução máxima para fotos antes de inserir na página A4 (mantém nitidez ~200 DPI e arquivo leve).
     */
    protected int $maxDimension = 1600;

    /**
     * Margem da folha A4 em milímetros.
     */
    protected int $marginMm = 12;

    /**
     * Qualidade de compressão JPEG.
     */
    protected int $jpegQuality = 85;

    /**
     * Processa e adiciona uma imagem como uma página A4 no PDF.
     */
    public function addImageAsPage(Fpdi $fpdi, string $imagePath): void
    {
        $imageData = @file_get_contents($imagePath);
        if (!$imageData) {
            return;
        }

        $gdImg = @imagecreatefromstring($imageData);
        if (!$gdImg) {
            return;
        }

        // 1. Corrige rotação baseada em EXIF (comum em fotos tiradas de celular)
        $gdImg = $this->fixExifOrientation($gdImg, $imagePath);

        // 2. Redimensiona fotos excessivamente pesadas
        $gdImg = $this->optimizeResolution($gdImg);

        $width = imagesx($gdImg);
        $height = imagesy($gdImg);

        // 3. Salva como JPEG normalizado em arquivo temporário compatível com FPDF
        $tempJpg = tempnam(sys_get_temp_dir(), 'img_pg_') . '.jpg';
        imagejpeg($gdImg, $tempJpg, $this->jpegQuality);
        imagedestroy($gdImg);

        try {
            $this->renderToPage($fpdi, $tempJpg, $width, $height);
        } finally {
            if (file_exists($tempJpg)) {
                @unlink($tempJpg);
            }
        }
    }

    /**
     * Corrige a orientação da imagem caso contenha metadados EXIF.
     */
    protected function fixExifOrientation(\GdImage $gdImg, string $imagePath): \GdImage
    {
        if (!function_exists('exif_read_data')) {
            return $gdImg;
        }

        $exif = @exif_read_data($imagePath);
        if (empty($exif['Orientation'])) {
            return $gdImg;
        }

        return match ($exif['Orientation']) {
            3 => imagerotate($gdImg, 180, 0),
            6 => imagerotate($gdImg, -90, 0),
            8 => imagerotate($gdImg, 90, 0),
            default => $gdImg,
        };
    }

    /**
     * Redimensiona proporcionalmente imagens que excedem a dimensão máxima definida.
     */
    protected function optimizeResolution(\GdImage $gdImg): \GdImage
    {
        $origWidth = imagesx($gdImg);
        $origHeight = imagesy($gdImg);

        if ($origWidth <= $this->maxDimension && $origHeight <= $this->maxDimension) {
            return $gdImg;
        }

        $scale = min($this->maxDimension / $origWidth, $this->maxDimension / $origHeight);
        $newWidth = (int) round($origWidth * $scale);
        $newHeight = (int) round($origHeight * $scale);

        $resized = imagecreatetruecolor($newWidth, $newHeight);
        $white = imagecolorallocate($resized, 255, 255, 255);
        imagefilledrectangle($resized, 0, 0, $newWidth, $newHeight, $white);
        imagecopyresampled($resized, $gdImg, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);
        imagedestroy($gdImg);

        return $resized;
    }

    /**
     * Desenha a imagem na página A4, centralizando e respeitando margens e proporção.
     */
    protected function renderToPage(Fpdi $fpdi, string $jpgPath, int $origWidth, int $origHeight): void
    {
        $orientation = ($origWidth > $origHeight) ? 'L' : 'P';
        $fpdi->AddPage($orientation, 'A4');

        $pageWidth = ($orientation === 'L') ? 297 : 210;
        $pageHeight = ($orientation === 'L') ? 210 : 297;

        $maxWidth = $pageWidth - ($this->marginMm * 2);
        $maxHeight = $pageHeight - ($this->marginMm * 2);

        $scale = min($maxWidth / $origWidth, $maxHeight / $origHeight);
        $renderWidth = $origWidth * $scale;
        $renderHeight = $origHeight * $scale;

        $x = $this->marginMm + (($maxWidth - $renderWidth) / 2);
        $y = $this->marginMm + (($maxHeight - $renderHeight) / 2);

        $fpdi->Image($jpgPath, $x, $y, $renderWidth, $renderHeight, 'JPEG');
    }
}
