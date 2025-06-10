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
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        $userRole = $request->user()->role;

        if (in_array($userRole, $roles)) {
            return $next($request);
        }

        // Redirect based on role
        if ($userRole === 'admin') {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('penduduk.dashboard');
        }
    }
} 