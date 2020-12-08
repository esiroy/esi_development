<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Lesson;
use App\Models\User;
use App\Models\Tutor;
use App\Models\Shift;
use App\Models\Member;
use App\Models\Status;

use Gate;
use Validator;
use Input;
use DB;
use Auth;

class Dashboard extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {    
        $user = Auth::user();
        $member = Member::where('user_id', $user->id)->first();

        if (isset($member)) 
        {
            $memberData = Member::find($member->id);
            $skypeID    = $memberData->communication_app_username; 
            $tutorData = Tutor::find($member->main_tutor_id);
            $lecturer   = $tutorData->name_en;

            $data = [
                'lecturer'  => $lecturer,
                'skypeID'   => $skypeID,            
            ];  

            return view('modules/member/index', compact('member', 'data'));
        } else {
            //member not found
            abort (404);
        }
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
