<?php

namespace App\Http\Controllers\Admin\Modules;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\MiniTestCategory;
use App\Models\MiniTestQuestion;

use Auth, Gate, Validator;
use Symfony\Component\HttpFoundation\Response;

class MiniTestQuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id, Request $request)
    {

        abort_if(Gate::denies('minitest_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $added_question_id = $request->added_question_id ?? null;
        $updated_question_id = $request->updated_question_id ?? null;

        $category = MiniTestCategory::find($id);
        
        $items = MiniTestQuestion::where('category_id', $id)
                    ->where('valid', true)
                    ->orderBy('id', 'DESC')
                    ->paginate(Auth::user()->items_per_page);

        return view('admin.modules.minitest.questions.index', compact('category', 'items', 'id', 'added_question_id', 'updated_question_id'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         abort_if(Gate::denies('minitest_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        abort_if(Gate::denies('minitest_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        //Validate Form
        $validator = Validator::make($request->all(), [
            'question' => ['required'],
        ]);

        if ($validator->fails()) {

            return redirect()->back()->withErrors($validator)->withInput()->with('error_message', 'Question is required');

        } else {


            $created = MiniTestQuestion::create([
                'category_id'   => $request->category_id,
                'question'      => $request->question,
                'valid'         => true
            ]);

            if ($created) {
            
                return redirect()->route('admin.minitest.questions.index', [ 'category_id'=> $request->category_id, 'added_question_id' => $created->id ])->with('message', 'Question added successfully!');

            } else {        

                return redirect()->route('admin.minitest.questions.index', [ 'category_id'=> $request->category_id, 'added_question_id' => $created->id ])->with('error_message', 'Question was not added due to an error, please try again!');        
            }        
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

        abort_if(Gate::denies('minitest_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');   


        echo "showing test question not available";
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($category_id, $question_id)
    {

        abort_if(Gate::denies('minitest_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $category = MiniTestCategory::find($category_id);


        $item = MiniTestQuestion::where('category_id', $category_id)
                    ->where('id', $question_id)
                    ->where('valid', true)
                    ->first();

        return view('admin.modules.minitest.questions.edit', compact('item', 'category', 'category_id', 'question_id'));

       
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $category_id, $question_id)
    {

         abort_if(Gate::denies('minitest_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

          //Validate Form
        $validator = Validator::make($request->all(), [
            'question' => ['required'],
        ]);

        if ($validator->fails()) {

            return redirect()->back()->withErrors($validator)->withInput()->with('error_message', 'Question is required');

        } else {


            $item = MiniTestQuestion::where('category_id', $category_id)
                        ->where('id', $question_id)
                        ->first();

            $updated = $item->update([
                'id'            => $question_id,
                'category_id'   => $category_id,
                'question'      => $request->question,
                'valid'         => true
            ]);


            if ($updated) {
            
                return redirect()->route('admin.minitest.questions.index', [ 'category_id'=> $request->category_id, 'updated_question_id' => $question_id ])->with('message', 'Question added successfully!');

            } else {        

                return redirect()->route('admin.minitest.questions.index', [ 'category_id'=> $request->category_id, 'updated_question_id' => $question_id ])->with('error_message', 'Question was not added due to an error, please try again!');        
            }      
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($category_id, $id)
    {
         abort_if(Gate::denies('minitest_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
         
        $item = MiniTestQuestion::where('valid', true)->where('id', $id)->first();

        if ($item) {
            
            $updated = $item->update(['valid' => false]);

            if ($updated) 
            {        
                return redirect()->route('admin.minitest.questions.index', [ 'category_id'=> $category_id ])->with('message', 'Question deleted successfully!');

            } else {
            
                return redirect()->route('admin.minitest.questions.index', [ 'category_id'=> $category_id ])->with('error_message', 'Question was not deleted due to an error, please try again!');        
            }

        } else {
        
            return redirect()->route('admin.minitest.questions.index', [ 'category_id'=> $category_id ])->with('error_message', 'Question was not found or must have been deleted already, please try again later');

        
        }


    }
}