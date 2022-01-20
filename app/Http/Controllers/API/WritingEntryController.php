<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AgentTransaction;
use App\Models\WritingEntries;
use App\Models\Member;
use Auth;

class WritingEntryController extends Controller
{

    public function checkCredits(Request $request, Member $member, AgentTransaction $agentTransaction, WritingEntries $writingEntries) 
    {     
        $userID         = $request->userID;
        $tutorID        = $request->tutorID;
        $memberInfo     = $member->where('user_id', Auth::user()->id )->first();
        $message        = null;

        if (Auth::user()->user_type == 'ADMINISTRATOR' || Auth::user()->user_type == 'MANAGER' || Auth::user()->user_type == "TUTOR") 
        {
            //MANAGER CAN GO SUBMIT ANYHTHING ON WRITING
            return Response()->json([
                "success"           => true,
                "message"           => "Account is credit for this type of account is not applicable",
                "totalPointsLeft"   => 1
            ]);

        } else if (Auth::user()->user_type == 'MEMBER' ) {
        
             //Get the submitted deduction points
             $wordPointDeduction = $writingEntries->getWordPointDeduct($request->words);

             //double the deduction if client selected a teacher
             if (isset($tutorID)) {
                $wordPointDeduction = $wordPointDeduction * 2;
             }

            if ($memberInfo->membership == "Monthly") 
            {
                $credits = $member->getMonthlyLessonsLeft();
                $totalPointsLeft = $credits - $wordPointDeduction;
                $errorMessage = "Sorry, you don't have enough monthly credits";

            } else if ($memberInfo->membership  == "Point Balance" ||  $memberInfo->membership  == "Both") {

                $credits = $agentTransaction->getCredits( Auth::user()->id ); 
                $totalPointsLeft = $credits - $wordPointDeduction;

                $errorMessage = "Sorry, you don't have enough point credits";

            }
        }        

        if ($totalPointsLeft < 0) 
        {
            $success =  false;
            $message = $errorMessage;
            $message .= "<p>You need to reload $wordPointDeduction points, You have a balance of $credits points </p>";
        } else {
            $success =  true;
            $message = "Congratulations, You have sufficient credits";
        }

        return Response()->json([
            "success"  => $success,   
            "message" =>  $message,
            "membership" => $memberInfo->membership,
            "credits"   => $credits,
            "totalDeductedPoints" => $wordPointDeduction,
            "membership" => $memberInfo->membership,
            "getMonthlyLessonsLeft" => $getMonthlyLessonsLeft ?? "not applicable",
            "totalPointsLeft" => $totalPointsLeft
        ]); 
    }
}