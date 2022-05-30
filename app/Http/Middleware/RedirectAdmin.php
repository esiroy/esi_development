<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Support\Facades\Auth;

class RedirectAdmin
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

        if (Auth::user()->roles->contains('title', 'Admin')) {
            return redirect(route('admin.dashboard.index'));
        } else {
            return $next($request);
        }
    }
}
