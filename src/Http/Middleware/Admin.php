<?php

namespace  Brackets\AdminAuth\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\UnauthorizedException;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     * @throws AuthenticationException
     */
    public function handle($request, Closure $next)
    {
        if ( Auth::check() && Auth::user()->can('admin') )
        {
            return $next($request);
        }

        if (!Auth::check()) {
            return redirect()->guest('/admin/login');
        } else {
            throw new UnauthorizedException('Unathorized');
        }
    }
}
