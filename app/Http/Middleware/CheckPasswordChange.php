<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPasswordChange
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
       if(auth()->check() && auth()->user()->must_change_password)
       {
        // si on n'est pas déjà sur la page de changement
        if(!$request->is('password/change'))
        {
            return redirect()->route('password.change.form');
        }
       }
       return $next($request);
    }
}
