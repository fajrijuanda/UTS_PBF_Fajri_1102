<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $jwt = $request->bearerToken();
        if (is_null($jwt)){
            return response()->json(['Akses Ditolak'], 422);
        }

        $decode = JWT::decode($jwt, new Key(env('JWT_SECRET_KEY'), 'HS256'));
        var_dump($decode);
        if ($$decode->role == 'user') {
            return $next($request);
        }

        if ($decode->role == 'user' && $request->is('categories*')) {
            return response()->json(['Anda tidak memiliki hak akses'], 422);
        }
        
        return response()->json(['Anda tidak memiliki hak akses admin'], 422);
    }
}