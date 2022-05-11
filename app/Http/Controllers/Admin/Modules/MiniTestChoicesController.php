<?php

namespace App\Http\Controllers\Admin\Modules;

use App\Http\Controllers\Controller;
use App\Models\MiniTestAnswerKey;
use App\Models\MiniTestChoice;
use App\Models\MiniTestQuestion;
use App\Models\MiniTestCategory;
use Auth;
use Illuminate\Http\Request;

class MiniTestChoicesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($category_id, $question_id, Request $request)
    {

        $added_choice_id = $request->added_choice_id ?? null;
        $updated_choice_id = $request->updated_choice_id ?? null;


        $category = MiniTestCategory::find($category_id);
     
        $items = MiniTestChoice::where('question_id', $question_id)
            ->where('valid', true)
            ->orderBy('id', 'DESC')
            ->paginate(Auth::user()->items_per_page);

        //Info Current page
        $item = MiniTestQuestion::where('id', $question_id)->where('valid', true)->first();

        return view('admin.modules.minitest.choices.index', compact('items', 'item', 'category', 'category_id', 'question_id', 'added_choice_id', 'updated_choice_id'));
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
    public function store($category_id, $question_id, Request $request)
    {

        $created = MiniTestChoice::create([
            'question_id' => $question_id,
            'choice' => $request->choice,
            'valid' => true,
        ]);

        if ($created) {
            
            if (isset($request->correct)) {

                $answerKey = MiniTestAnswerKey::where('question_id', $question_id)->first();

                if ($answerKey) {

                    $answerKey->update([
                        'choice_id' => $created->id,
                    ]);

                } else {
                    MiniTestAnswerKey::create([
                        'question_id' => $question_id,
                        'choice_id' => $created->id,
                    ]);
                }

            }

            return redirect()->route('admin.minitest.choices.index', ['category_id' => $category_id, 'question_id' => $question_id, 'added_choice_id' => $created->id])->with('message', 'Choice added successfully!');

        } else {

            return redirect()->route('admin.minitest.choices.index', ['category_id' => $category_id, 'question_id' => $question_id, 'added_choice_id' => $created->id])->with('error_message', 'Choice was not added due to an error, please try again!');
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
    public function edit($category_id, $question_id, $choice_id)
    {
        $item = MiniTestChoice::where('id', $choice_id)->where('valid', true)->first();


        $category = MiniTestCategory::find($category_id);


        //Info Current page
        $question = MiniTestQuestion::where('id', $question_id)->where('valid', true)->first();

        return view('admin.modules.minitest.choices.edit', compact('item', 'question', 'category', 'category_id', 'question_id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($category_id, $question_id, $choice_id, MiniTestChoice $miniTestChoice, Request $request)
    {

        $choice = $miniTestChoice->where('id', $choice_id)->first();

        if ($choice) {
            
            $updated = $choice->update([
                'question_id'   => $question_id,
                'choice'        => $request->choice,
                'valid'         => true,
            ]);

            if ($updated) {
                
                if (isset($request->correct)) {

                    $answerKey = MiniTestAnswerKey::where('question_id', $question_id)->first();

                    if ($answerKey) {

                        

                        $answerKey->update([
                            'choice_id' => $choice->id,
                        ]);

                    } else {
                        MiniTestAnswerKey::create([
                            'question_id' => $question_id,
                            'choice_id' => $choice->id,
                        ]);
                    }

                }

                return redirect()->route('admin.minitest.choices.index', ['category_id' => $category_id, 'question_id' => $question_id, 'updated_choice_id' => $choice->id])->with('message', 'Choice updated successfully!');

            } else {

                return redirect()->route('admin.minitest.choices.index', ['category_id' => $category_id, 'question_id' => $question_id, 'updated_choice_id' => $choice->id])->with('error_message', 'Choice was not added due to an error, please try again!');
            }

        } else {
        
            echo "choice not found";
        
        }
   
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($category_id, $question_id, $choice_id)
    {
        $item = MiniTestChoice::where('valid', true)->where('id', $choice_id)->first();

        if ($item) {
            
            $updated = $item->update(['valid' => false]);

            if ($updated) 
            {        

                $answerKey = MiniTestAnswerKey::where('question_id', $question_id)->where('choice_id', $choice_id)->first();

                if ($answerKey) {
                    $answerKey->delete();
                }

                return redirect()->route('admin.minitest.choices.index', ['category_id' => $category_id, 'question_id' => $question_id ])->with('message', 'Choice deleted successfully!');

            } else {
            
                return redirect()->route('admin.minitest.choices.index', ['category_id' => $category_id, 'question_id' => $question_id ])->with('error_message', 'Choice was not deleted due to an error, please try again!');        
            }

        } else {
        
            return redirect()->route('admin.minitest.choices.index', ['category_id' => $category_id, 'question_id' => $question_id ])->with('error_message', 'Choice was not found or must have been deleted already, please try again later');

        
        }

    }
}
