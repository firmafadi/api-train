<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Traits\ApiResponse;
class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    use ApiResponse;

    public function handle(Request $request, Closure $next): Response
    {
        // dd($request->getUser());
        if ($request->getUser() == 'admin'){
            return $next($request);
        }
        return $this->setResponse(null, 400, "Unauthorize user credential");
    }
}
