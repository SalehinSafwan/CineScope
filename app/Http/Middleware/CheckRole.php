<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    // this is the simple role guard for admin-only pages
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (! Auth::check() || Auth::user()->role !== $role) {
            return redirect()->route('home')->with('error', "Nope, this page requires the {$role} role.");
        }

        return $next($request);
    }
}