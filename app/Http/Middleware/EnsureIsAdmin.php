<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureIsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // role-less মানে frontend user, admin panel এ ঢুকতে পারবে না
        if (!$user || $user->roles->isEmpty()) {
            return redirect()->route('home.index');
        }

        return $next($request);
    }
}