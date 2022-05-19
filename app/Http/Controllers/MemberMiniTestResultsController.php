<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\MiniTestResult;


use Auth;

class MemberMiniTestResultsController extends Controller
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
        //
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

        $ctr    = 1;
        $result = MiniTestResult::leftJoin('question_categories', 'question_categories.id', '=', 'member_test_results.question_category_id')
                        ->where('member_test_results.id', $id)
                        ->where('member_test_results.user_id', Auth::user()->id)
                        ->first();
        if ($result) 
        {
            $items = json_decode($result->member_answers);
            return view("modules.minitest.result.show", compact('result', 'items', 'ctr')); 
        } else {
        
              abort(404);
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
