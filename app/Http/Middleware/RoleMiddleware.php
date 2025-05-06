<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    // Mapping role name => role_id
    protected $roleMap = [
        'admin' => 1,
       'panitia' => 2,
       'dosen' => 3,
        'mahasiswa' => 4,
    ];

    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(403, 'Unauthorized');
        }

        // Konversi role string menjadi role_id
        $allowedRoleIds = collect($roles)->map(function ($role) {
            return $this->roleMap[$role] ?? null;
        })->filter()->all();

        if (!in_array($user->role_id, $allowedRoleIds)) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
