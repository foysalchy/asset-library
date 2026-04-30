<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FrontendAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
         if (!auth()->check()) {
            return redirect()->route('frontend.signin');
        }

        if (auth()->user()->hasRole('super_admin') || auth()->user()->isSuperAdmin()) {
            abort(403);
        }
        return $next($request);
    }
}
