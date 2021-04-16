<?php

namespace App\Http\Controllers\Admin\Modules;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Tutor;
use App\Models\Member;
use App\Models\Lesson;
use App\Models\ScheduleItem;
use App\Models\userImage;
use App\Models\Questionnaire;
use App\Models\QuestionnaireItem;
use Auth;

class QuestionnaireController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $items_per_page = Auth::user()->items_per_page;        

        if (isset($request->date_from) && isset($request->date_to)) 
        {

            $dateFrom = date('Y-m-d', strtotime($request['date_from']));
            $dateTo = date('Y-m-d', strtotime($request['date_to']));            

            $questionnaires = Questionnaire::whereBetween('schedule_item.lesson_time', [$dateFrom, $dateTo])
                                ->join('schedule_item', 'questionnaire.schedule_item_id', '=', 'schedule_item.id')
                                ->select('schedule_item.lesson_time', 'questionnaire.*');

        } else {            
            $questionnaires = Questionnaire::orderBy('questionnaire.id', 'ASC')
                            ->join('schedule_item', 'questionnaire.schedule_item_id', '=', 'schedule_item.id')
                            ->select('schedule_item.lesson_time', 'questionnaire.*');
        }        

        $questionnaires = $questionnaires->orderBy('schedule_item.lesson_time', 'DESC');
        $questionnaires = $questionnaires->paginate($items_per_page);        

        return view('admin.modules.questionnaires.index', compact('questionnaires'));
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


        try {

            $scheduleItem = ScheduleItem::find($id);

            $member = Member::where('user_id', $scheduleItem->member_id)->first();
            $userImage = UserImage::where('user_id', $member->user_id)->first();
            
            //Tutor
            $tutor  = Tutor::where('user_id',  $scheduleItem->tutor_id)->first();
    
            //Questions
            $questionnaire = Questionnaire::where('schedule_item_id', $id)->first();
    
            $questionnaireID =  $questionnaire->id;   
            $questionnaireItem1 = QuestionnaireItem::where('questionnaire_id',  $questionnaireID)
                                ->where('QUESTION', "QUESTION_1")->first();
           
            $questionnaireItem2 = QuestionnaireItem::where('questionnaire_id', $questionnaireID)
                                ->where('QUESTION', "QUESTION_2")->first();
    
            $questionnaireItem3 = QuestionnaireItem::where('questionnaire_id', $questionnaireID)
                                ->where('QUESTION', "QUESTION_3")->first();
    
            $questionnaireItem4 = QuestionnaireItem::where('questionnaire_id', $questionnaireID)
                                ->where('QUESTION', "QUESTION_4")->first();
    
            return view('admin.modules.questionnaires.show', compact('scheduleItem', 'userImage', 'member', 'tutor', 'questionnaire', 'questionnaireItem1', 'questionnaireItem2', 'questionnaireItem3', 'questionnaireItem4'));

        } catch (\Exception $e) {

            echo "<div style='color:red'>". $e->getMessage() . " on Line : " . $e->getLine() . "</div>";

            return false;
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
