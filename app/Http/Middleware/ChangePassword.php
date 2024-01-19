<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ChangePassword
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
        if (auth()->check() && auth()->user()->created_at == auth()->user()->updated_at){
            return redirect()->route('perfil');
        }


        return $next($request);
    }
}
