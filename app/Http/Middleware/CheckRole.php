<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect('/');
        }

        $userRole = trim(strtolower(Auth::user()->role));

        $roles = array_map(fn($role) => strtolower(trim($role)), $roles);

        if (!in_array($userRole, $roles)) {
            abort(403, 'Acesso não autorizado.');
        }

        return $next($request);
    }
}