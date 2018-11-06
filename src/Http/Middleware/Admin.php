<?php

namespace Brackets\AdminAuth\Http\Middleware;

use Closure;
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
     */
    public function handle($request, Closure $next)
    {
        if (Auth::guard(config('admin-auth.defaults.guard'))->check() && Auth::guard(config('admin-auth.defaults.guard'))->user()->can('admin')) {
            return $next($request);
        }

        if (!Auth::guard(config('admin-auth.defaults.guard'))->check()) {
            return redirect()->guest('/admin/login');
        } else {
            throw new UnauthorizedException('Unathorized');
        }
    }
}
