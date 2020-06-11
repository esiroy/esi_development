<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {

            if (strtolower($request->segment(1)) == 'admin') {
       
                $roles[] = Auth::user()->roles()->get()->pluck('title');
                
                if (array_intersect(['admin','moderator'], $roles)) {

                    return redirect(route('admin.dashboard.index'));

                } else {
                    return redirect(RouteServiceProvider::HOME);
                }

            } else {
                return redirect(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }
}
