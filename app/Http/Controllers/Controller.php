<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Gate;
use Closure;

use Symfony\Component\HttpFoundation\Response;
use App\Providers\RouteServiceProvider;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /* 
    * Check if current user has site wide admin access @value: admin_access
    * Deny access if the current user has no privilidge to view admin access */
    public function __construct(Request $request, $guard = null) {
        if (strtolower($request->segment(1)) == 'admin') {
            /*
            $this->middleware(function ($request, $next) {
                abort_if(Gate::denies('admin_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
                return $next($request);
            });*/

            $this->middleware(function ($request, $next) 
            {
                $roles = Auth::user()->roles()->get()->pluck('title');
                foreach ($roles as $role) {
                    $user_roles[] = strtolower($role);
                }
                if (array_intersect(['user'], $user_roles)) {
                    return redirect(RouteServiceProvider::HOME);
                } else {
                    abort_if(Gate::denies('admin_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
                    return $next($request);
                }
            });
        
        }
    }


}