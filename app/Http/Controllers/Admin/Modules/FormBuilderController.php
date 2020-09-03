<?php

namespace App\Http\Controllers\Admin\Modules;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

use App\Models\Form;

use Gate;
use Validator;
use Input;
use DB;
use Auth;

class FormBuilderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(Gate::denies('formbuilder_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $can_user_delete_forms = (Gate::denies('formbuilder_delete')) ? 'false' : 'true';

        $forms = Form::all();

        return view('admin.modules.formbuilder.index', compact('forms'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Gate::denies('formbuilder_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $forms = form::all();

        return view('admin.modules.formbuilder.create', compact('forms'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        abort_if(Gate::denies('formbuilder_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        //disallow duplicate form name
        $validator = Validator::make($request->all(), [
            'form_name'        => 'required|unique:forms|max:191',
            'form_description' => [
                'max:191'
            ]
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.module.formbuilder.create')->withErrors($validator)->withInput();
        }
       
        //Create form 
        $form = form::create([
            'form_name'           => $request['form_name'],
            'form_description'    => $request['form_description']
        ]);

        return redirect(route('admin.module.formbuilder.show', $form->id))->with('message', 'Form has been create successfully!');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        abort_if(Gate::denies('formbuilder_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');   
      
       
        $form = Form::find($id);

        if ($form) {

            return view('admin.modules.formbuilder.show', compact('form'));

        } else {

            return redirect( route('admin.module.formbuilder.index') )->with('error_message', 'Folder cant be found, it may have been deleted already.');
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
        $forms = null;
        
        return view('admin.modules.formbuilder.edit', compact('forms'));
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
