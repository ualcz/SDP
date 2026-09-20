<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckResponsavel
{
    /**
     * Manipula a requisição recebida.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Se não estiver logado ou não for responsável por nenhum setor, nega o acesso
        if (!auth()->check() || !auth()->user()->ehResponsavel()) {
            abort(403, 'Acesso não autorizado. Apenas responsáveis de setor podem acessar esta área.');
        }

        return $next($request);
    }
}
