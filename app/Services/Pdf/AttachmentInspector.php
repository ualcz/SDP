<?php

namespace App\Services\Pdf;

use Illuminate\Http\UploadedFile;

class AttachmentInspector
{
    /**
     * Resolve o caminho físico local do arquivo (UploadedFile ou caminho em string).
     */
    public function getRealPath(mixed $arquivo): ?string
    {
        if ($arquivo instanceof UploadedFile) {
            return $arquivo->getRealPath();
        }

        if (is_string($arquivo)) {
            return $arquivo;
        }

        return null;
    }

    /**
     * Obtém o MIME type do arquivo.
     */
    public function getMimeType(mixed $arquivo, string $caminho): string
    {
        if ($arquivo instanceof UploadedFile) {
            return $arquivo->getMimeType() ?: 'application/octet-stream';
        }

        if (function_exists('mime_content_type')) {
            $mime = @mime_content_type($caminho);
            if ($mime) {
                return $mime;
            }
        }

        return 'application/octet-stream';
    }

    /**
     * Obtém a extensão original do arquivo em letras minúsculas.
     */
    public function getExtension(mixed $arquivo, string $caminho): string
    {
        if ($arquivo instanceof UploadedFile) {
            $ext = $arquivo->getClientOriginalExtension();
            if ($ext) {
                return strtolower($ext);
            }
        }

        return strtolower(pathinfo($caminho, PATHINFO_EXTENSION));
    }

    /**
     * Verifica se o arquivo é um documento PDF.
     */
    public function isPdf(mixed $arquivo, string $caminho): bool
    {
        $mime = $this->getMimeType($arquivo, $caminho);
        if ($mime === 'application/pdf') {
            return true;
        }

        return $this->getExtension($arquivo, $caminho) === 'pdf';
    }

    /**
     * Verifica se o arquivo é uma imagem suportada.
     */
    public function isImage(mixed $arquivo, string $caminho): bool
    {
        $mime = $this->getMimeType($arquivo, $caminho);
        if (str_starts_with($mime, 'image/')) {
            return true;
        }

        $ext = $this->getExtension($arquivo, $caminho);
        return in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp']);
    }
}
