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
            if ($request->header('X-SIGNATURE')) {
                $granted = $this->isGranted($request);
                if (!$granted['status']) {
                    return $this->setResponse(null, 400, $granted['message']);
                }
                return $next($request);
            }else{
                return $this->setResponse(null, 400, "Invalid header SIGNATURE");
            }
        }else{
            return $this->setResponse(null, 400, "Invalid header TIMESTAMP");
        }
    }

    public function isGranted($req)
    {
        if (!$req->header('X-MERCHANT')) {
            return [
                'status'=>false,
                'message'=>'Invalid header MERCHANT'
            ];
        }

        $signature = $req->header('X-SIGNATURE');
        $sign = explode(':', base64_decode($signature));
        // dd($sign);
        if ($sign[0] != 'employee') {
            return [
                'status'=>false,
                'message'=>'Invalid header SIGNATURE[1]'
            ];
        }
        if ($sign[1] != date('Y-m-d')) {
            return [
                'status'=>false,
                'message'=>'Invalid header SIGNATURE[2]'
            ];
        }
        if ($sign[2] != $req->header('X-MERCHANT')) {
            return [
                'status'=>false,
                'message'=>'Invalid header SIGNATURE[3]'
            ];
        }

        return [
            'status'=>true,
            'message'=>'sukses'
        ];
    }
}
