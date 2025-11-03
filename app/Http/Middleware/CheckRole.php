<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Cek apakah user sudah login
        if (!auth()->check()) {
            return redirect('/login');
        }

        // Cek apakah user punya role yang sesuai
        if (!auth()->user()->hasRole($role)) {
            // Tampilkan error 403 dengan pesan
            abort(403, 'ROLE TIDAK VALID');
        }

        return $next($request);
    }
}