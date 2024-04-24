<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;

use Closure;

class Users
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            if(Auth::user()->accreditor == "none"){
                return $next($request);
            }
        }

        if (Auth::guard($guard)->check()) {
            if(Auth::user()->superadmin == "none"){
                return $next($request);
            }
        }

        return response()->view('template.errors.404', [], 404);
    }
}
