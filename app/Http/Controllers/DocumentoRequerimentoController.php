<?php

namespace App\Http\Controllers;

use App\Models\DocumentoRequerimento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class DocumentoRequerimentoController extends Controller
{
    /**
     * Valida se o usuário autenticado tem permissão para acessar o documento.
     */
    private function autorizarAcesso(DocumentoRequerimento $documento): void
    {
        $usuario = auth()->user();
        if (!$usuario) {
            abort(401, 'Usuário não autenticado.');
        }

        $requerimento = $documento->requerimento;
        if (!$requerimento) {
            abort(404, 'Requerimento associado não encontrado.');
        }

        // 1. Administrador tem acesso irrestrito
        if ($usuario->isAdmin()) {
            return;
        }

        // 2. Aluno proprietário do requerimento
        if ((int) $requerimento->usuario_id === (int) $usuario->id) {
            return;
        }

        // 3. Servidor responsável pelo setor ou com papel de servidor/professor
        if ($usuario->ehResponsavelDoSetor($requerimento->setor_id) || $usuario->isServidor()) {
            return;
        }

        abort(403, 'Você não possui permissão para visualizar ou baixar este documento.');
    }

    /**
     * Visualização inline do arquivo (para iframes, imagens ou nova aba).
     */
    public function visualizar(DocumentoRequerimento $documento)
    {
        $this->autorizarAcesso($documento);

        if (!Storage::disk('local')->exists($documento->caminho)) {
            abort(404, 'O arquivo solicitado não foi encontrado no servidor.');
        }

        $caminhoFisico = Storage::disk('local')->path($documento->caminho);
        $mime = $documento->mime_type ?: (mime_content_type($caminhoFisico) ?: 'application/octet-stream');

        return response()->file($caminhoFisico, [
            'Content-Type'        => $mime,
            'Content-Disposition' => 'inline; filename="' . rawurlencode($documento->nome_original) . '"',
            'Cache-Control'       => 'private, max-age=3600',
        ]);
    }

    /**
     * Download direto do arquivo com o nome original.
     */
    public function baixar(DocumentoRequerimento $documento)
    {
        $this->autorizarAcesso($documento);

        if (!Storage::disk('local')->exists($documento->caminho)) {
            abort(404, 'O arquivo solicitado não foi encontrado no servidor.');
        }

        return Storage::disk('local')->download($documento->caminho, $documento->nome_original);
    }
}
