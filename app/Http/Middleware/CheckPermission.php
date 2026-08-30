<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {

        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // ZeeLinks admin
        if ($user->email === 'zeelinks1slamabad@gmail.com') {
            return $next($request);
        }

        // API access
        if ($request->routeIs('api-keys*')) {
            if (!$user->api_access) {
                abort(403, 'You do not have API access.');
            }

            return $next($request);
        }

        // Admin-only pages
        if ($request->routeIs('permissions*')) {
            abort(403);
        }

        return $next($request);
    }
}