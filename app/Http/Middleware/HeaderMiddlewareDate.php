<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Traits\ApiResponse;

class HeaderMiddlewareDate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    
    use ApiResponse;

    public function handle(Request $request, Closure $next): Response
    {
        // dd(date('d/m/Y'));
        if ($request->header('X-TIMESTAMP') && $request->header('X-TIMESTAMP') == date('d/m/Y')) {
            return $next($request);
        }

        return $this->setResponse(null, 400, "Invalid header request");
    }
}
