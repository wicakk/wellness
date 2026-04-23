<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ThemeMiddleware
{
    public function handle(Request $request, Closure $next): mixed
    {
        if (auth()->check()) {
            $theme = auth()->user()->theme_preference ?? 'light';
            session(['theme' => $theme]);
        }

        return $next($request);
    }
}
