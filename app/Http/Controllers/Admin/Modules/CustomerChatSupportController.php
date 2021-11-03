<?php

namespace App\Http\Controllers\Admin\Modules;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class CustomerChatSupportController extends Controller
{

    public function __construct()
    {    
        $this->middleware(function ($request, $next) {
            //authenticated by has no "admin_access" in his role attached
            //@do: redirect to home (authenticated member will be his view)
            if (Gate::denies('admin_access')) {
                return redirect(route('home'));
            }
            return $next($request);           
        });
    }
    
    
    public function index() {

        abort_if(Gate::denies('tutor_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        return view('admin.modules.customerchatsupport.index');
    }
}