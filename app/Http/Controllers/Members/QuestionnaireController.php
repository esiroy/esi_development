<?php

namespace App\Http\Controllers\Members;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Lesson;
use App\Models\User;
use App\Models\Tutor;
use App\Models\Shift;
use App\Models\Member;
use App\Models\Status;
use App\Models\ScheduleItem;
use App\Models\ReportCard;
use App\Models\ReportCardDate;
use App\Models\Questionnaire;
use App\Models\QuestionnaireItem;



use Gate;
use Validator;
use Input;
use DB;
use Auth;



class QuestionnaireController extends Controller
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
        $id = $request->scheduleitemid;

        $questionnaire = Questionnaire::where('schedule_item_id', $id)->first();    
        
        if (isset($questionnaire->id)) {
            $newQuestionnaire = $questionnaire->update([
                'schedule_item_id' => $id,
                'remarks' =>  $request->remarks,
                'tutor_id' => $request->tutor_id,
                'member_id' => Auth::user()->id,
                'valid' => true
            ]);

            $questionnaireID = $questionnaire->id;

        } else {
            $newQuestionnaire = Questionnaire::create([
                'schedule_item_id' => $id,
                'remarks' =>  $request->remarks,
                'tutor_id' => $request->tutor_id,
                'member_id' => Auth::user()->id,
                'valid' => true
            ]);         
            
            $questionnaireID = $newQuestionnaire->id;
        }




        if (isset($request->QUESTION_1grade))
        {

            $questionnaireItem = QuestionnaireItem::
                                where('questionnaire_id',  $questionnaireID)
                                ->where('QUESTION', "QUESTION_1")
                                ->first();

            if (isset($questionnaireItem->id)) {
                $questionnaireItem->update([
                    'questionnaire_id' =>  $questionnaireID,
                    'QUESTION' => 'QUESTION_1',
                    'grade' =>  $request->QUESTION_1grade,
                    'valid' => true,
                ]);
            } else {
                $data = QuestionnaireItem::create([
                    'questionnaire_id' =>  $questionnaireID,
                    'QUESTION' => 'QUESTION_1',
                    'grade' =>  $request->QUESTION_1grade,
                    'valid' => true,
                ]);
            }

        }

        if (isset($request->QUESTION_2grade))
        {

            $questionnaireItem = QuestionnaireItem::
                                where('questionnaire_id', $questionnaireID)
                                ->where('QUESTION', "QUESTION_2")
                                ->first();


            if (isset($questionnaireItem->id)) {
                $questionnaireItem->update([
                    'questionnaire_id' =>  $questionnaireID,
                    'QUESTION' => 'QUESTION_2',
                    'grade' =>  $request->QUESTION_2grade,
                    'valid' => true,
                ]);
            } else {
                $data = QuestionnaireItem::create([
                    'questionnaire_id' =>  $questionnaireID,
                    'QUESTION' => 'QUESTION_2',
                    'grade' =>  $request->QUESTION_2grade,
                    'valid' => true,
                ]);
            }
        }
        

        if (isset($request->QUESTION_3grade))
        {

            $questionnaireItem = QuestionnaireItem::
                                where('questionnaire_id', $questionnaireID)
                                ->where('QUESTION', "QUESTION_3")
                                ->first();


            if (isset($questionnaireItem->id)) {
                $questionnaireItem->update([
                    'questionnaire_id' =>  $questionnaireID,
                    'QUESTION' => 'QUESTION_3',
                    'grade' =>  $request->QUESTION_3grade,
                    'valid' => true,
                ]);
            } else {
                $data = QuestionnaireItem::create([
                    'questionnaire_id' =>  $questionnaireID,
                    'QUESTION' => 'QUESTION_3',
                    'grade' =>  $request->QUESTION_3grade,
                    'valid' => true,
                ]);
            }
        }

        if (isset($request->QUESTION_4grade))
        {

            $questionnaireItem = QuestionnaireItem::
                                where('questionnaire_id', $questionnaireID)
                                ->where('QUESTION', "QUESTION_4")
                                ->first();


            if (isset($questionnaireItem->id)) {
                $questionnaireItem->update([
                    'questionnaire_id' =>  $questionnaireID,
                    'QUESTION' => 'QUESTION_4',
                    'grade' =>  $request->QUESTION_4grade,
                    'valid' => true,
                ]);
            } else {
                $data = QuestionnaireItem::create([
                    'questionnaire_id' =>  $questionnaireID,
                    'QUESTION' => 'QUESTION_4',
                    'grade' =>  $request->QUESTION_4grade,
                    'valid' => true,
                ]);
            }
        }        

       

        return redirect('questionnaire/'. $id)->with('message', 'ご協力ありがとうございました。');

       
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, ReportCard $reportcards)
    {      
        $user = Auth::user();
        $member = Member::where('user_id', $user->id)->first();
        $latestReportCard = $reportcards->getLatest($member->user_id);


        if ($member) {

            $questionnaire = Questionnaire::where('schedule_item_id', $id)->first();
    
            if (isset($questionnaire->id)) {

                //EDIT : found the questionnaire 
                $questionnaireItem1 = QuestionnaireItem::where('questionnaire_id', $questionnaire->id)->where('question', "QUESTION_1")->first();
                $questionnaireItem2 = QuestionnaireItem::where('questionnaire_id', $questionnaire->id)->where('question', "QUESTION_2")->first();
                $questionnaireItem3 = QuestionnaireItem::where('questionnaire_id', $questionnaire->id)->where('question', "QUESTION_3")->first();
                $questionnaireItem4 = QuestionnaireItem::where('questionnaire_id', $questionnaire->id)->where('question', "QUESTION_4")->first();
                
                return view('modules.questionnaire.edit', compact('member',  'questionnaire', 'questionnaireItem1', 'questionnaireItem2', 'questionnaireItem3', 'questionnaireItem4'));

            } else {  
                //CREATE: new questionnaire 
                $scheduleItem = ScheduleItem::find($id);

                if ($scheduleItem) {


                    $questionnaireItem1 = null;
                    $questionnaireItem2 = null;
                    $questionnaireItem3 = null;
                    $questionnaireItem4 = null;
                    
                    return view('modules.questionnaire.create', compact('member', 'scheduleItem'));

                } else {
                    
                    abort(404);
                }


            }          
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
        $questionnaire = Questionnaire::where('schedule_item_id', $id)->first();

        if (isset($questionnaire->id)) {
            $newQuestionnaire = $questionnaire->update([
                'schedule_item_id' => $id,
                'remarks' =>  $request->remarks,
                'tutor_id' => $request->tutor_id,
                'member_id' => Auth::user()->id,
                'valid' => true
            ]);

            $questionnaireID = $questionnaire->id;

        } else {
            $newQuestionnaire = Questionnaire::create([
                'schedule_item_id' => $id,
                'remarks' =>  $request->remarks,
                'tutor_id' => $request->tutor_id,
                'member_id' => Auth::user()->id,
                'valid' => true
            ]);         
            
            $questionnaireID = $newQuestionnaire->id;
        }

        

        if (isset($request->QUESTION_1grade))
        {

            $questionnaireItem = QuestionnaireItem::
                                where('questionnaire_id',  $questionnaireID)
                                ->where('QUESTION', "QUESTION_1")
                                ->first();

            if (isset($questionnaireItem->id)) {
                $questionnaireItem->update([
                    'questionnaire_id' =>  $questionnaireID,
                    'QUESTION' => 'QUESTION_1',
                    'grade' =>  $request->QUESTION_1grade,
                    'valid' => true,
                ]);
            } else {
                $data = QuestionnaireItem::create([
                    'questionnaire_id' =>  $questionnaireID,
                    'QUESTION' => 'QUESTION_1',
                    'grade' =>  $request->QUESTION_1grade,
                    'valid' => true,
                ]);
            }

        }

        if (isset($request->QUESTION_2grade))
        {

            $questionnaireItem = QuestionnaireItem::
                                where('questionnaire_id', $questionnaireID)
                                ->where('QUESTION', "QUESTION_2")
                                ->first();


            if (isset($questionnaireItem->id)) {
                $questionnaireItem->update([
                    'questionnaire_id' =>  $questionnaireID,
                    'QUESTION' => 'QUESTION_2',
                    'grade' =>  $request->QUESTION_2grade,
                    'valid' => true,
                ]);
            } else {
                $data = QuestionnaireItem::create([
                    'questionnaire_id' =>  $questionnaireID,
                    'QUESTION' => 'QUESTION_2',
                    'grade' =>  $request->QUESTION_2grade,
                    'valid' => true,
                ]);
            }
        }
        

        if (isset($request->QUESTION_3grade))
        {

            $questionnaireItem = QuestionnaireItem::
                                where('questionnaire_id', $questionnaireID)
                                ->where('QUESTION', "QUESTION_3")
                                ->first();


            if (isset($questionnaireItem->id)) {
                $questionnaireItem->update([
                    'questionnaire_id' =>  $questionnaireID,
                    'QUESTION' => 'QUESTION_3',
                    'grade' =>  $request->QUESTION_3grade,
                    'valid' => true,
                ]);
            } else {
                $data = QuestionnaireItem::create([
                    'questionnaire_id' =>  $questionnaireID,
                    'QUESTION' => 'QUESTION_3',
                    'grade' =>  $request->QUESTION_3grade,
                    'valid' => true,
                ]);
            }
        }

        if (isset($request->QUESTION_4grade))
        {

            $questionnaireItem = QuestionnaireItem::
                                where('questionnaire_id', $questionnaireID)
                                ->where('QUESTION', "QUESTION_4")
                                ->first();


            if (isset($questionnaireItem->id)) {
                $questionnaireItem->update([
                    'questionnaire_id' =>  $questionnaireID,
                    'QUESTION' => 'QUESTION_4',
                    'grade' =>  $request->QUESTION_4grade,
                    'valid' => true,
                ]);
            } else {
                $data = QuestionnaireItem::create([
                    'questionnaire_id' =>  $questionnaireID,
                    'QUESTION' => 'QUESTION_4',
                    'grade' =>  $request->QUESTION_4grade,
                    'valid' => true,
                ]);
            }
        }
        
        
        return redirect('questionnaire/'. $id)->with('message', 'ご協力ありがとうございました。');
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
