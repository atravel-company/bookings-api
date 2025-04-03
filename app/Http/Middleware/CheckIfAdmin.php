<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckIfAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user()->hasRole('admin')) {
            return response()->view('errors.403', ['message' => 'You do not have admin access.'], 403);
        }

        return $next($request);
    }
}
