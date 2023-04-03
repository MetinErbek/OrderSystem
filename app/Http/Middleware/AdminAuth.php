<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;


class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        //echo json_encode(Auth::guard('api')->user());exit;
        if (Auth::guard('api')->user() && Auth::guard('api')->user()->role == 'admin') {
            return $next($request);
        }
        return jsonResponse(FALSE, 'No Permission !', [], 403 );
        

    }
}
