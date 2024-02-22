<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Traits\ApiResponse;

class HeaderMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    
    use ApiResponse;

    public function handle(Request $request, Closure $next): Response
    {
        if ($request->header('X-TIMESTAMP') && $request->header('X-TIMESTAMP') == date('Y-m-d')) {
            return $next($request);
        }

        return $this->setResponse(null, 400, "Invalid header request");
    }
}
