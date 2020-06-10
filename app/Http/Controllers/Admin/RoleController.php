<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\Models\Role;
use App\Models\Permission;

use Gate;
use Validator;
use Input;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        abort_if(Gate::denies('role_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::all();

        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Gate::denies('role_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $permissions = Permission::all()->pluck('title', 'id');

        return view('admin.roles.create', compact('permissions'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        abort_if(Gate::denies('role_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        //disallow duplicate role name
        $validator = Validator::make($request->all(), 
        [
            'title' => [
                'required',
                'max:190',
                Rule::unique('roles')->whereNull('deleted_at'),
            ]
        ]);

        if ($validator->fails()) {

            return redirect()->route('admin.roles.create')->withErrors($validator)->withInput();

        } else {

            $role = Role::create($request->all());

            $role->permissions()->sync($request->input('permissions', []));

            return redirect()->route('admin.roles.index')->with('message', 'Role has been added successfully!');
        }


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        abort_if(Gate::denies('role_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $role->load('permissions');

        return view('admin.roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        abort_if(Gate::denies('role_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $permissions = Permission::all()->pluck('title', 'id');

        $role->load('permissions');

        return view('admin.roles.edit', compact('permissions', 'role'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        abort_if(Gate::denies('role_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        //disallow duplicate permission name
        $validator = Validator::make($request->all(), 
        [
            'title' => [
                'required',
                'max:190',
                Rule::unique('roles')->ignore($role->id)->whereNull('deleted_at'),
            ]
        ]);

        if ($validator->fails()) {

            //Validation Failed - Redirect with input errors
            return redirect()->route('admin.roles.edit', $role->id)->withErrors($validator)->withInput();

        } else {
            
            //update record (role, sync them with permissions) and redirect with sucess message
            $role->update($request->all());
            $role->permissions()->sync($request->input('permissions', []));

            return redirect()->route('admin.roles.index')->with('message', 'Role has been updated successfully!');
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        abort_if(Gate::denies('role_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $role->delete();

        return back()->with('message', 'Role has been deleted successfully!');
    }

    public function massDestroy(Request $request)
    {
        abort_if(Gate::denies('role_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        Role::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);

    }

}