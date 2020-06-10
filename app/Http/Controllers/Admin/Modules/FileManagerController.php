<?php

namespace App\Http\Controllers\Admin\Modules;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;

use App\Models\Folder;
use Gate;
use Validator;
use Input;

class FileManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        abort_if(Gate::denies('filemanager_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $folders = Folder::all();
        

        return view('admin.modules.filemanager.index', compact('folders'));
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
        abort_if(Gate::denies('filemanager_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

       
        $folder = Folder::find($id);

        if ($folder) {

            $folders = Folder::get();

            $files  = Folder::find($folder->id)->files;
            
            return view('admin.modules.filemanager.show', compact('folders', 'folder', 'files'));

        } else {

            return redirect( route('uploader.index') )->with('error_message', 'Folder cant be found, it may have been deleted already.');
        }


        
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
