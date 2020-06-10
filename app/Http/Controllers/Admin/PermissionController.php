<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\Models\Permission;

use Gate;
use Validator;
use Input;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(Gate::denies('permission_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $permissions = Permission::all();

        return view('admin.permissions.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Gate::denies('permission_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.permissions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        abort_if(Gate::denies('permission_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        //disallow duplicate permission name
        $validator = Validator::make($request->all(), 
        [
            'title' => [
                'required',
                'max:190',
                Rule::unique('permissions')->whereNull('deleted_at'),
            ]
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.permissions.create')->withErrors($validator)->withInput();
        } else {
            $permission = Permission::create($request->all());
            return redirect()->route('admin.permissions.index')->with('message', 'Permission has been added successfully!');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Permission $permission)
    {
        abort_if(Gate::denies('permission_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.permissions.show', compact('permission'));
    }

    public function edit(Permission $permission)
    {
        abort_if(Gate::denies('permission_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.permissions.edit', compact('permission'));
    }

    public function update(Request $request, Permission $permission)
    {
        abort_if(Gate::denies('permission_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        //disallow duplicate permission name
        $validator = Validator::make($request->all(), 
        [
            'title' => [
                'required',
                'max:190',
                Rule::unique('permissions')->ignore($permission->id)->whereNull('deleted_at'),
            ]
        ]);

        if ($validator->fails()) {

            //Validation Failed - Redirect with input errors
            return redirect()->route('admin.permissions.edit', $permission->id)->withErrors($validator)->withInput();

        } else {
            
            //redirect with sucess message
            $permission->update($request->all());
            return redirect()->route('admin.permissions.index')->with('message', 'Permission has been updated successfully!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Permission $permission)
    {
        abort_if(Gate::denies('permission_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $permission->delete();

        return back()->with('message', 'Permission has been deleted successfully!');
    }

    public function massDestroy(Request $request)
    {
        abort_if(Gate::denies('permission_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        Permission::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);

    }


}
