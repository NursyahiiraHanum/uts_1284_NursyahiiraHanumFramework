<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\User;
use App\Http\Middleware\Auth;

class AuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {

        $jwt = $request->bearerToken();

        if (is_null($jwt) || $jwt == '') {
            return response()->json([
                'msg' => 'Akses ditolak, token tidak memenuhi'
            ], 401);
        } else {
            try {


                $jwtDecoded = JWT::decode($jwt, new Key(env('JWT_SECRET_KEY'), 'HS256'));

                // Ambil pengguna dari payload token
                $user = User::find($jwtDecoded->sub);

                if (!$user) {
                    return response()->json([
                        'msg' => 'Akses ditolak, pengguna tidak ditemukan'
                    ], 401);
                }

                $request->merge(['auth' => $user]);




                return $next($request);
            } catch (\Exception $e) {


                return response()->json([
                    'msg' => 'Token tidak valid'
                ], 401);
            }
        }
    }
}