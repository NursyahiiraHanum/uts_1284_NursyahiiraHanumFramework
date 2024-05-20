<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {


        if (
            $request->auth && $request->auth->role === 'admin'
        ) {
            return $next($request);
        }

        return response()->json([
            'msg' => 'Akses ditolak, hanya admin yang dapat mengakses'
        ], 403);
    }
}