<?php

namespace App\Http\Controllers;

use App\Models\DocumentoAssunto;
use App\Services\ModeloDocumentoService;

class DocumentoAssuntoController extends Controller
{
    public function baixarModelo(DocumentoAssunto $documento, ModeloDocumentoService $service)
    {
        abort_unless($documento->assunto?->ativo && $documento->assunto?->setor?->ativo, 404);

        $caminho = $service->gerarPdfParaAluno($documento, auth()->user());

        return response()->download($caminho, $service->nomeArquivo($documento), [
            'Content-Type' => 'application/pdf',
        ])->deleteFileAfterSend(true);
    }

}
