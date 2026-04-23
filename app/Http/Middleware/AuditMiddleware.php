<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuditMiddleware
{
    protected array $skip = ['audit-logs', 'notifications'];

    public function handle(Request $request, Closure $next): mixed
    {
        $response = $next($request);

        if (auth()->check() && $request->isMethod('GET') && !str_contains($request->path(), 'api')) {
            foreach ($this->skip as $s) {
                if (str_contains($request->path(), $s)) return $response;
            }

            \App\Models\AuditLog::record(
                action: 'page_view',
                model: null,
                modelId: null,
                old: [],
                new: ['path' => $request->path()]
            );
        }

        return $response;
    }
}
