<?php

namespace App\Http\Controllers\Admin\Modules;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\Models\User;
use App\Models\Member;
use App\Models\Attribute;
use App\Models\Membership;
use App\Models\Shift;

use DB;


class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //@todo:attributes
        $attributes = Attribute::all();
        $memberships = Membership::all();
        
        $shifts = Shift::all();

        /*
        $members = User::select("*", DB::raw("CONCAT(users.first_name,' ',users.last_name) as full_name"))
                   ->whereHas('roles', function($q) { $q->where('title', 'Member'); })->get();   
        */
        $members = Member::join('users', 'users.id', '=', 'members.user_id')
                    ->join('attributes', 'attributes.id', '=', 'members.member_attribute_id')
                    ->select("*", DB::raw("CONCAT(users.first_name,' ',users.last_name) as full_name, attributes.name as attribute"))
                    ->get();

        
        $tutorQuery = User::whereHas('roles', function($q) { $q->where('title', 'tutor'); })->get();         
        $tutors = json_encode($tutorQuery);

        return view('admin.modules.member.index', compact('memberships', 'shifts', 'attributes', 'tutors', 'members'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.modules.member.create');
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
