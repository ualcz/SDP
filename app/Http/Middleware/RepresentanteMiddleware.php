<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RepresentanteMiddleware
{
    public function handle($request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        if (!Auth::user()->representanteAtivo()) {
            abort(403, 'Acesso não autorizado.');
        }

        return $next($request);
    }
}
?>