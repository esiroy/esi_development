<?php

namespace App\Http\Controllers\API;

use App;
use App\Http\Controllers\Controller;
use App\Models\MemberExamScore;
use Auth;
use Illuminate\Http\Request;

class MemberExamController extends Controller
{
    public function getMemberExamScoreByType(Request $request)
    {
        $examScoreList = null;
        $examScoreLink = null;

        $memberID = isset($request['memberID']) ? $request['memberID'] : Auth::user()->id;

        $examTypes = MemberExamScore::where('user_id', $memberID)->select('exam_type')->distinct()->get();

        if (count($examTypes) >= 1) {
            foreach ($examTypes as $value) 
            {
                $examType = $value->exam_type;
                $types[] = $examType;
                //get list of scores
                $examScores = MemberExamScore::where('user_id', $memberID)->where('exam_type', $examType)->orderBy('id', 'DESC')->get();
            
                $scores = $examScores;

                $examScoreList[$examType]['perPage'] = 1;            
                $examScoreList[$examType]['rows'] =  $examScores->count();

                foreach ($examScores as $examScore) {
                     $item = array_merge( ['date'=> ESIDateFormat($examScore->exam_date)], json_decode($examScore->exam_scores, true));
                    $examScoreList[$examType]['items'][] = $item;
                }
            }
        
            return Response()->json([
                'success' => true,
                'examTypes' => $types,               
                'examScoreList' => $examScoreList,
            ]);
        } else {
            return Response()->json([
                'success' => false,
                'message' => 'No member examination record found ',
            ]);
        }
    }


    public function getMemberScoreHistory(Request $request) {
        $examScoreList = null;
        $examScoreLink = null;
        $memberID = isset($request['memberID']) ? $request['memberID'] : Auth::user()->id;

        $examTypes = MemberExamScore::where('user_id', $memberID)->select('exam_type')->distinct()->get();
        if (count($examTypes) >= 1) {

            foreach ($examTypes as $value) 
            {
                $examType = $value->exam_type;
                $types[] = $examType;

                $examScores = MemberExamScore::where('user_id', $memberID)->where('exam_type', $examType)->orderBy('exam_date', 'ASC')->get();
                $total = 0;
            

                foreach ($examScores as $examScore) 
                {
                    $scoreData = json_decode($examScore->exam_scores, true);

                    if (isset($scoreData['total'])) 
                    {                    
                        $total = $scoreData['total'];

                    } else if (isset($scoreData['overallBandScore'])) {

                        $total = $scoreData['overallBandScore'];
                        
                    } else if ($value->exam_type == "Other_Test") {

                        $total =  $scoreData['otherScore'];

                    }

                    $examScoreList[$examType]['dates'][] =  ESIDateFormat($examScore->exam_date);
                    $examScoreList[$examType]['totals'][] =  $total; 
                    
                }

            }

            return Response()->json([
                'success' => true,
                'examTypes' => $types,
                'examScoreList' => $examScoreList,
            ]);
        } else {
            return Response()->json([
                'success' => false,
                'message' => 'No member examination record found',
            ]);
        
        }


    }

    public function getAllMemberExamScore(Request $request)
    {
        $limit = $request->get('limit');

        $memberID = isset($request['memberID']) ? $request['memberID'] : Auth::user()->id;

        $examScores = MemberExamScore::where('user_id', $memberID);
        $scores = $examScores->orderBy('id', 'DESC')->simplePaginate($limit);

        $examTypes = MemberExamScore::where('user_id', $memberID)->select('exam_type')->distinct()->get();

        $links = $scores->appends(['sort' => 'scores'])->links();

        if ($scores) {
            return Response()->json([
                'success' => true,
                'scores' => view('modules.member.popup.showAllMemberExamList', compact('scores', 'examTypes'))->render(),
                'links' => $links,
            ]);
        } else {
            return Response()->json([
                'success' => false,
                'message' => 'No record found for member',
            ]);
        }
    }

    public function getMemberLatestScore(Request $request, MemberExamScore $memberExamScore)
    {
        $memberID = isset($request['memberID']) ? $request['memberID'] : Auth::user()->id;
        return $memberExamScore->getMemberLatestScore($memberID);
    }

    public function addMemberExamScore(Request $request, MemberExamScore $memberExamScore)
    {
        $memberID = (isset($request['memberID'])) ? $request['memberID'] : Auth::user()->id;

        $examDate = $request['examDate'];
        $examType = $request['examType'];


        if (!($examDate == '' || $examType == '')) 
        {
            if ($examType == "EIKEN") {
            
                $examLevel  = $request['examLevel'];
                $examScores = $request['examScore'];


                if ($examLevel == "") {
                
                  return Response()->json([
                        'success' => false,
                        'message' => 'All fields are required, please check if the examination date, type and scores are all filled up',
                    ]);

                } else {
                
                    if ($examLevel > 3) 
                    {
                        $grade =   $examScores['grade_'.$examLevel];

                        if (isset($grade) ) 
                        {
                            $examScores = [
                                            "exam_level" => ucwords(str_replace('_', ' ', $examLevel)),
                                            'grade_' =>  $grade, 
                                            "total" => $grade 
                                        ];
                            $json_scores = json_encode($examScores);

                            $data = [
                                'examDate' => $examDate,
                                'examType' => $examType  . "_Grade_". $examLevel,
                                'examScores' => $json_scores,
                            ];

                            $memberExamScore->addScore($memberID, $data);

                            return Response()->json([
                                'success' => true,
                                'message' => 'Member Score Added',
                                'examDate' => ESIDateFormat($examDate),
                                'examType' => str_replace('_', ' ', $examType) . " Grade ". $examLevel ,
                                'examScores' => $json_scores,
                            ]);

                        } else {                        
                            return Response()->json([
                                'success' => false,
                                'message' => 'Please check if all scores are filled up',
                            ]);                       
                        }
                    } else {

                         $grade_s1 =   $examScores['grade_'.$examLevel .'_1st_stage'];
                         $grade_s2 =   $examScores['grade_'.$examLevel .'_2nd_stage'];


                        //check level 1 and level 2
                        if (isset($grade_s1) && isset($grade_s2)) 
                        {
                            $examScores = [
                                            "exam_level" => ucwords(str_replace('_', ' ', $examLevel)),
                                            'grade_'.$examLevel."_1st_stage" =>  $grade_s1,  
                                            'grade_'.$examLevel."_2nd_stage" =>  $grade_s2,  
                                            "total" => $grade_s1 + $grade_s2
                                        ];
                            $json_scores = json_encode($examScores);

                            $data = [
                                'examDate' => $examDate,
                                'examType' => $examType . "_Grade_". $examLevel,
                                'examScores' => $json_scores,
                            ];

                            $memberExamScore->addScore($memberID, $data);

                            return Response()->json([
                                'success' => true,
                                'message' => 'Member Score Added',
                                'examDate' => ESIDateFormat($examDate),
                                'examType' => str_replace('_', ' ', $examType),
                                'examScores' => $json_scores,
                            ]);

                        } else {                        
                            return Response()->json([
                                'success' => false,
                                'message' => 'Please check if all scores are filled up',
                            ]);                       
                        }

                    
                    }  
                
                }

            } else {
                $scores = $request['examScore'];
                $json_scores = json_encode($request['examScore']);         

                if ($memberExamScore->isScoresMissing($scores) == true) {
                    return Response()->json([
                        'success' => false,
                        'message' => 'All fields are required, please check if the examination date, type and scores are all filled up',
                    ]);
                } else {
                
                    $data = [
                        'examDate' => $examDate,
                        'examType' => $examType,
                        'examScores' => $json_scores,
                    ];
                    $memberExamScore->addScore($memberID, $data);

                    return Response()->json([
                        'success' => true,
                        'message' => 'Member Score Added',
                        'examDate' => ESIDateFormat($examDate),
                        'examType' => str_replace('_', ' ', $examType), 
                        'examScores' => $json_scores,
                    ]);
                }
            }

        } else {
            return Response()->json([
                'success' => false,
                'message' => 'All fields are required, please check if the examination date, type and scores are all filled up',
            ]);
        }
    }
}
