<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    public function handle(
        Request $request,
        Closure $next,
        string ...$roles
    ): Response {
        $user = session('auth_user', []);
        $currentRole = $user['role'] ?? '';

        // Samakan alias role administrator
        if ($currentRole === 'Administrator') {
            $currentRole = 'Admin Sistem';
        }

        if (!in_array($currentRole, $roles, true)) {
            abort(403);
        }

        return $next($request);
    }
}
