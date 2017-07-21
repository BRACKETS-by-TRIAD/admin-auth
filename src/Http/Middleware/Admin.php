<?php

namespace  Brackets\AdminAuth\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;

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

        //TODO maybe return to login with not authorized message
        throw new AuthenticationException('Unauthenticated.');
    }
}
