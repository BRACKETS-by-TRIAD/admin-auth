<?php

namespace  Brackets\AdminAuth\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;

class ApplyUserLocale
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
        if ( Auth::check() && isset(Auth::user()->language))
        {
            app()->setLocale(Auth::user()->language);
        }

        return $next($request);
    }
}
