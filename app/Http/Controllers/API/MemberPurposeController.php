<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Purpose;

use Auth, App;
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
                'target_score'      => $target_score,      
                'purpose_list'      => $purposelist,
                "purpose"           => $purpose,
                "purpose_option"    => $purpose_option,   
                      
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
                'speaking' => $request['IELTS_speaking'],
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
            Purpose::create([          
                'valid' => 1,
                'purpose' => $request['TOEFL'],
                'purpose_options' => json_encode($request['TOEFL_option']),
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
            Purpose::create([          
                'valid' => 1,
                'purpose' => $request['TOEIC'],
                'purpose_options' => json_encode($request['TOEIC_option']),
                'member_id' => $user->id
            ]);
        }


        //EIKEN
        if (isset($request['EIKEN'])) {
            Purpose::create([          
                'valid' => 1,
                'purpose' => $request['EIKEN'],
                'purpose_options' => json_encode($request['EIKEN_option']),
                'member_id' => $user->id
            ]);
        }            


        //TEAP
        if (isset($request['TEAP'])) {
            Purpose::create([          
                'valid' => 1,
                'purpose' => $request['TEAP'],
                'purpose_options' => json_encode($request['TEAP_option']),
                'member_id' => $user->id
            ]);
        }                 
            

        //BUSINESS
        if (isset($request['BUSINESS'])) {
            Purpose::create([          
                'valid' => 1,
                'purpose' => $request['BUSINESS'],
                'purpose_options' => json_encode($request['BUSINESS_option']),
                'member_id' => $user->id
            ]);
        }                 


        //BUSINESS_CAREERS
        if (isset($request['BUSINESS_CAREERS'])) {
            Purpose::create([          
                'valid' => 1,
                'purpose' => $request['BUSINESS_CAREERS'],
                'purpose_options' => json_encode($request['BUSINESS_CAREERS_option']),
                'member_id' => $user->id
            ]);
        }         


        //DAILY_CONVERSATION
        if (isset($request['DAILY_CONVERSATION'])) {
            Purpose::create([          
                'valid' => 1,
                'purpose' => $request['DAILY_CONVERSATION'],
                'purpose_options' => json_encode($request['DAILY_CONVERSATION_option']),
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
