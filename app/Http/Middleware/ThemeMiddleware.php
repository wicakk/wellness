<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ThemeMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $theme = auth()->user()->theme_preference ?? 'light';
            session(['theme' => $theme]);
        }

        return $next($request);
    }
}