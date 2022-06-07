<?php

namespace App\Http\Controllers\Admin\Modules;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\MiniTestSetting;

use Auth, Gate, Validator, DB;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\Rule;

class MiniTestSettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(MiniTestSetting $miniTestSetting)
    {
        abort_if(Gate::denies('minitest_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $miniTestLimit      = $miniTestSetting->getMiniTestLimit();
        $miniTestDuration   = $miniTestSetting->getMiniTestDuration();
    
        return view('admin.modules.minitest.settings.index', compact('miniTestLimit', 'miniTestDuration'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, MiniTestSetting $miniTestSetting)
    {
        DB::beginTransaction();

        try {

            if (isset($request->limit)) 
            {
                $limit = $request->limit;
                $miniTestSetting->createOrUpdateLimit($limit);
            }

            if (isset($request->duration)) {
                $duration =  $request->duration;
                $miniTestSetting->createOrUpdateDuration($duration);
            }


             DB::commit();

             return redirect()->route('admin.minitest.settings.index')->with('message', 'Settings added successfully!');

        
        } catch (\Exception $e) {
            

            DB::rollBack();

           $error = $e->getMessage();
    
           
           return redirect()->route('admin.minitest.settings.index')->with('error_message', "Error: Settings was not updated, please try again later! <br> $error");
        }
         
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
