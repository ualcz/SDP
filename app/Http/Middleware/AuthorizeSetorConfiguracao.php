<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthorizeSetorConfiguracao
{
    public function handle(Request $request, Closure $next): Response
    {
        $usuario = $request->user();

        if (!$usuario || ($usuario->role !== 'admin' && !$usuario->ehResponsavel())) {
            abort(403, 'Acesso não autorizado.');
        }

        return $next($request);
    }
}