<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckClient
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
public function handle($request, Closure $next)
    {
        // Se o usuario for Client, vai pra proxima rota
        if (auth()->check() && auth()->user()->role == 'client') {
            return $next($request);
        }

        // Se o usuario for Admin, vai pra proxima rota também
        if (auth()->check() && auth()->user()->role == 'admin') {
            return $next($request);
        }
        
        // Se não for nenhum, não está logado, ou deu erro na Role
        return response('Não autorizado!', 401);
    }
}
