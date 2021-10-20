<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Validator;

class CheckBlocked
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(auth()->check() && isset(auth()->user()->proveedor)){
            if(auth()->user()->proveedor->bloqueado) {
                auth()->logout();
                $validator = Validator::make([], []);
                $validator->errors()->add('email', 'Tu cuenta está bloqueada.');
                return redirect()->route('login')->withErrors($validator);
            }
        }
        return $next($request);
    }
}
