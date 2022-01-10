<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Purpose;
use Auth,App;
use DB;
class MemberPurposeController extends Controller
{
    public function getMemberPurposeList(Request $request) 
    {
        $purpose = Purpose::where('member_id', Auth::user()->id)->orderBy('id', 'ASC')->get();

        if (count($purpose) >= 1) {
            return Response()->json([
                "success"       => true,
                "message"       => "Purpose found for member",
                'content'       => view('modules.member.includes.showMemberPurposeList', compact('purpose'))->render()
            ]);
        } else {
            return Response()->json([
                "success"       => false,
                "message"       => "No record found for member",
                'content'       => view('modules.member.includes.showMemberPurposeList', compact('purpose'))->render()
            ]);
        }
    }

    /** 
    ** @description: Show the Purpose form 
    */
    public function getMemberPurpose(Request $request) 
    {        
        $purposelist = Purpose::where('member_id', Auth::user()->id)->orderBy('id', 'ASC')->get();
        $purpose = array();  
        $purpose_option = array();
        $target_score = array();

        foreach ($purposelist as $key => $list) 
        {
            $purpose[str_replace(' ', '_', $list->purpose)] = $list->purpose;   

            if ($list->purpose == "OTHERS") 
            {
                $purpose_option["OTHERS"] = $list->purpose_options;
                $target_score['OTHERS'] = $list->target_scores;
                
            } else {
                if (isset($list->purpose_options))
                {
                    $options = (array)json_decode($list->purpose_options);
                    if (is_array($options)) {
                        foreach ($options  as $option) {
                            $purpose_option[str_replace(' ', '_', $list->purpose) ."_". str_replace(' ', '_', $option) ] =  $option;
                        }                
                    }
                    //Get the target scores for each purpose
                    $targetScores = (array)json_decode($list->target_scores);
                    if (is_array($targetScores)) {
                        foreach ($targetScores as $targetScoreKey => $score) {
                            $target_score[str_replace(' ', '_', $list->purpose) ."_". str_replace(' ', '_', ucfirst($targetScoreKey)) ] =  $score;
                        }
                    }
                }
            }             
        }
        
        if ($purpose) {
            return Response()->json([
                "success"           => true,                
                //"purpose"           => $purpose,
                //'purpose_list'      => $purposelist,               

                "purpose_option"    => $purpose_option,
                'target_score'      => $target_score,      
                'purposeForm'       => view('modules.member.includes.memberPurpose', compact('purpose', 'purpose_option', 'target_score'))->render()                
            ]);
        } else {
            return Response()->json([
                "success" => false,
                "message" => "No record found for member",
            ]);
        }
    }


    public function updateMemberPurpose(Request $request) 
    {
        $user = Auth::user();

        /********************************************
                    DELETE: OLD MEMBER PURPOSE
        **********************************************/
        Purpose::where('member_id', $user->id)->delete();

        /********************************************
                    CREATE MEMBER PURPOSE
        **********************************************/
        //IELTS
        if (isset($request['IELTS'])) {
            $IELTS_TargetScores = [
                'speaking' => $request['IELTS_Speaking'],
                'writing' => $request['IELTS_Writing'],
                'reading' => $request['IELTS_Reading'],
                'listening' => $request['IELTS_Listening']
            ];

            Purpose::create([          
                'valid' => 1,
                'purpose' => $request['IELTS'],
                'purpose_options' => json_encode($request['IELTS_option']),
                'target_scores' => json_encode($IELTS_TargetScores),
                'member_id' => $user->id
            ]);
        }

        //TOEFL
        if (isset($request['TOEFL'])) {

            $TOEFL_TargetScores = [
                'speaking' => $request['TOEFL_Speaking'],
                'writing' => $request['TOEFL_Writing'],
                'reading' => $request['TOEFL_Reading'],
                'listening' => $request['TOEFL_Listening']
            ];

            Purpose::create([          
                'valid' => 1,
                'purpose' => $request['TOEFL'],
                'purpose_options' => json_encode($request['TOEFL_option']),
                'target_scores' => json_encode($TOEFL_TargetScores),
                'member_id' => $user->id
            ]);
        }

        //TOEFL_Primary
        if (isset($request['TOEFL_Primary'])) {
            Purpose::create([          
                'valid' => 1,
                'purpose' => $request['TOEFL_Primary'],
                //'purpose_options' => json_encode($request['TOEFL_Primary_option']),
                'member_id' => $user->id
            ]);
        }

        //TOEIC
        if (isset($request['TOEIC'])) {

            $TOEIC_TargetScores = [
                'speaking' => $request['TOEIC_Speaking'],
                'writing' => $request['TOEIC_Writing'],
                'reading' => $request['TOEIC_Reading'],
                'listening' => $request['TOEIC_Listening']
            ];

            Purpose::create([          
                'valid' => 1,
                'purpose' => $request['TOEIC'],
                'purpose_options' => json_encode($request['TOEIC_option']),
                'target_scores' => json_encode($TOEIC_TargetScores),
                'member_id' => $user->id
            ]);
        }

        //EIKEN
        if (isset($request['EIKEN'])) {

            $EIKEN_TargetScores = [
                'grade_5' => $request['EIKEN_Grade_5'],
                'grade_4' => $request['EIKEN_Grade_4'],
                'grade_3' => $request['EIKEN_Grade_3_1st_Stage'],
                'grade_pre_2' => $request['EIKEN_Grade_Pre_2_1st_Stage'],
                'grade_2' => $request['EIKEN_Grade_2_1st_Stage'],
                'grade_pre_1' => $request['EIKEN_Grade_Pre_1_1st_Stage'],
                'grade_1' => $request['EIKEN_Grade_1_1st_Stage'],

            ];


            Purpose::create([          
                'valid' => 1,
                'purpose' => $request['EIKEN'],
                'purpose_options' => json_encode($request['EIKEN_option']),
                'target_scores' => json_encode($EIKEN_TargetScores),
                'member_id' => $user->id
            ]);
        }            


        //TEAP
        if (isset($request['TEAP'])) {

           $TEAP_TargetScores = [
                'speaking' => $request['TEAP_Speaking'],
                'writing' => $request['TEAP_Writing'],
                'reading' => $request['TEAP_Reading'],
                'listening' => $request['TEAP_Listening']
            ];

            Purpose::create([          
                'valid' => 1,
                'purpose' => $request['TEAP'],
                'purpose_options' => json_encode($request['TEAP_option']),
                'target_scores' => json_encode($TEAP_TargetScores),                
                'member_id' => $user->id
            ]);
        }                 
            

        //BUSINESS
        if (isset($request['BUSINESS'])) {

           $BUSINESS_TargetScores = [
                'basic' => $request['BUSINESS_Basic'],
                'intermediate' => $request['BUSINESS_Intermediate'],
                'advance' => $request['BUSINESS_Advance'],
            ];

            Purpose::create([          
                'valid' => 1,
                'purpose' => $request['BUSINESS'],
                'purpose_options' => json_encode($request['BUSINESS_option']),
                'target_scores' => json_encode($BUSINESS_TargetScores),
                'member_id' => $user->id
            ]);
        }                 


        //BUSINESS_CAREERS
        if (isset($request['BUSINESS_CAREERS'])) {

            $BUSINESS_CAREERS_TargetScores = [
                'medicine' => $request['BUSINESS_CAREERS_Medicine'],
                'nursing' => $request['BUSINESS_CAREERS_Nursing'],
                'pharmaceutical' => $request['BUSINESS_CAREERS_Pharmaceutical'],
                'accounting' => $request['BUSINESS_CAREERS_Accounting'],
                'legal_Professionals' => $request['BUSINESS_CAREERS_Legal_Professionals'],
                'finance' => $request['BUSINESS_CAREERS_Finance'],
                'technology' => $request['BUSINESS_CAREERS_Technology'],
                'commerce' => $request['BUSINESS_CAREERS_Commerce'],
                'tourism' => $request['BUSINESS_CAREERS_Tourism'],
                'cabin_Crew' => $request['BUSINESS_CAREERS_Cabin_Crew'],
                'marketing_and_Advertising' => $request['BUSINESS_CAREERS_Marketing_and_Advertising'],
            ];

            Purpose::create([          
                'valid' => 1,
                'purpose' => $request['BUSINESS_CAREERS'],
                'purpose_options' => json_encode($request['BUSINESS_CAREERS_option']),
                'target_scores' => json_encode($BUSINESS_CAREERS_TargetScores),
                'member_id' => $user->id
            ]);
        }         

        //DAILY_CONVERSATION
        if (isset($request['DAILY_CONVERSATION'])) {

           $DAILY_CONVERSATION_TargetScores = [
                'basic' => $request['DAILY_CONVERSATION_Basic'],
                'intermediate' => $request['DAILY_CONVERSATION_Intermediate'],
                'advance' => $request['DAILY_CONVERSATION_Advance'],
            ];

            Purpose::create([          
                'valid' => 1,
                'purpose' => $request['DAILY_CONVERSATION'],
                'purpose_options' => json_encode($request['DAILY_CONVERSATION_option']),                
                'target_scores' => json_encode($DAILY_CONVERSATION_TargetScores),
                'member_id' => $user->id
            ]);
        }

        //DAILY_CONVERSATION
        if (isset($request['OTHERS'])) {
            Purpose::create([          
                'valid' => 1,
                'purpose' => $request['OTHERS'],
                'purpose_options' => $request['OTHERS_value'],
                'member_id' => $user->id
            ]);
        }         

        return Response()->json([
            "success"        => true,
            "message"        => "Member Purpose updated successfully",
        ]);
    }
}