<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class KacabMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || auth()->user()->user_type !== 'kacab') {
            abort(403);
        }
        return $next($request);
    }
}