<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckProdiAccess
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Jika bukan panitia atau panitia jurusan (id_prodi null), bebas akses
        if ($user->role->name !== 'panitia' || is_null($user->id_prodi)) {
            return $next($request);
        }

        // Jika panitia prodi, batasi akses hanya ke prodi miliknya
        $targetProdiId = $request->route('prodi_id') ?? $request->input('prodi_id');

        if ($targetProdiId && $user->id_prodi != $targetProdiId) {
            abort(403, 'Akses ditolak: Anda hanya dapat mengakses data prodi Anda sendiri.');
        }

        return $next($request);
    }
}
