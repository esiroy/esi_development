<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Gate;


class DashboardController extends Controller
{
    /*
    public function __construct(Request $request) {
        $this->middleware(function ($request, $next) {

            abort_if(Gate::denies('admin_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

            return $next($request);
        });
    }*/

    
    public function index()
    {
        abort_if(Gate::denies('admin_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        //@can('filemanager_upload_delete)
        $can_user_create_folder  = (Gate::denies('filemanager_create')) ? 'false' : 'true';
        $can_user_edit_folder    = (Gate::denies('filemanager_edit')) ? 'false' : 'true';
        $can_user_delete_folder    = (Gate::denies('filemanager_delete')) ? 'false' : 'true';
        $can_user_upload         = (Gate::denies('filemanager_upload')) ? 'false' : 'true';
        $can_user_delete_uploads = (Gate::denies('filemanager_upload_delete')) ? 'false' : 'true';

        //$folders = (Folder::getFoldersRecursively());
        $folders = Array();
        $files = Array();
        
        return view('admin.dashboard', compact(
            'folders', 
            'files', 
            'can_user_upload',
            'can_user_delete_uploads', 
            'can_user_create_folder',
            'can_user_edit_folder',
            'can_user_delete_folder'
        ));

      
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
