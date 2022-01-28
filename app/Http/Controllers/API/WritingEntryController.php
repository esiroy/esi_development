<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AgentTransaction;
use App\Models\WritingEntries;
use App\Models\Member;
use App\Models\User;
use Auth, Config;

class WritingEntryController extends Controller
{

    public function checkCredits(Request $request, Member $member, AgentTransaction $agentTransaction, WritingEntries $writingEntries) 
    {     
        $userID         = $request->userID;
        $tutorID        = $request->tutorID;
        $memberInfo     = $member->where('user_id', Auth::user()->id )->first();
        $message        = null;

        if (Auth::user()->user_type == 'ADMINISTRATOR' || Auth::user()->user_type == 'MANAGER' ) 
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
                $errorMessage = "Sorry, you don't have enough monthly points for this entry.";

            } else if ($memberInfo->membership  == "Point Balance" ||  $memberInfo->membership  == "Both") {

                $credits = $agentTransaction->getCredits( Auth::user()->id ); 
                $totalPointsLeft = $credits - $wordPointDeduction;

                $errorMessage = "Sorry, you don't have enough points for this entry.";

            }
        }        



        if ($totalPointsLeft < 0) 
        {
            $success =  false;
            $message = $errorMessage;
            $message .= "<p>This entry requires $wordPointDeduction  points,  You only have  $credits point(s) left </p>";
        } else {
            $success =  true;
            $message = "<p>Congratulations, You have sufficient credits </p>";
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


    public function checkMemberCredits(Request $request, Member $member, AgentTransaction $agentTransaction, WritingEntries $writingEntries) 
    {     

        $wordPointDeduction = 0;

        $writingEntry = $writingEntries->find($request->entryID);

        if ($writingEntry) {
            $deposit = $writingEntry->total_points;
        } else {
            $deposit = 0;
        }

        $is_appointed   = $request->appointed;
        $memberID       = $request->memberID;        
        $tutorID        = $request->tutorID;
        $memberInfo     = $member->where('user_id', $memberID )->first();
        $user           = User::find($memberID);
        $message        = null;

        //Get the submitted deduction points
        if ($request->overrideWordCount == false || $request->overrideWordCount == 'false') 
        {
            $wordPointDeduction = $writingEntries->getWordPointDeduct($request->words);    
                
            if ( $wordPointDeduction == false) {
                return Response()->json([
                    "success"  => false,   
                    "message" => "800 words limit exceeded",               
                ]); 
            }    

            if ($is_appointed == 'on') {
                $wordPointDeduction = $wordPointDeduction * 2;
            }

            $totalDeduction = $wordPointDeduction;

            $wordPointDeduction = $wordPointDeduction - $deposit;
        }


        if ($user->user_type == 'ADMINISTRATOR' || $user->user_type == 'MANAGER' ) 
        {
            //MANAGER CAN GO SUBMIT ANYHTHING ON WRITING
            return Response()->json([
                "success"           => true,
                "message"           => "Account is credit for this type of account is not applicable",
                "totalPointsLeft"   => 1
            ]);

        } else if ($user->user_type == 'MEMBER' ) {

            if ($memberInfo->membership == "Monthly") 
            { 
                $credits = $member->getMemberMonthlyLessonsLeft($memberID);

                //get the deposit based (writing ID) total points when monthy
                //$totalPointsLeft = ($credits) - $wordPointDeduction;

                $totalCredits = $credits + $deposit;
                $totalPointsLeft =  $totalCredits - $totalDeduction;
                                
                if ($totalPointsLeft < 0) 
                {
                    $success =  false;
                    $message =  "Sorry, the member don't have enough monthly points for this entry.";
                    $message .= "<div>This entry requires $wordPointDeduction  addtional point(s), Member only have $credits credit(s) </div>";
                } else {
                    $success =  true;
                    $message = "<div>Congratulations, Member have sufficient credits </div>";
                }

                return Response()->json([
                    "success"           => $success,
                    "is_appointed"      => $is_appointed,
                    "message"           =>  $message,
                    "membership"        => $memberInfo->membership,
                    "credits"           => $credits,
                    "deposit"           => $deposit,
                    "totalCredits"      => $totalCredits,
                    'totalDeduction'    => $totalDeduction,
                    "wordPointDeduction" => $wordPointDeduction,
                    "totalPointsLeft"   => $totalPointsLeft
                ]); 


            } else if ($memberInfo->membership  == "Point Balance" ||  $memberInfo->membership  == "Both") {


                $credits = $agentTransaction->getCredits($memberID); 

                //get the deposit based (writing ID) total points when monthy
                $totalPointsLeft = ($credits + $deposit) - $wordPointDeduction;

                if (isset($request->hasAttachement)) {

                    $credits = $agentTransaction->getCredits( $memberID ); 
                    $totalPointsLeft = $credits - $wordPointDeduction;

                    if ($totalPointsLeft < 0) 
                    {
                        $success =  false;
                        $message =  "Sorry, the member don't have enough monthly points for this entry.";
                        $message .= "<div>This entry requires $wordPointDeduction  addtional point(s), Member only have $credits credit(s) </div>";
                    } else {
                        $success =  true;
                        $message = "<div>Congratulations, Member have sufficient credits </div>";
                    }
                    
                    return Response()->json([
                        "success"               => $success,   
                        "message"               => $message,
                        "membership"            => $memberInfo->membership,
                        "credits"               => $credits,
                        "deposit"               => $deposit,
                        "wordPointDeduction"    => $wordPointDeduction,
                        "totalPointsLeft"       => $totalPointsLeft
                    ]); 


                } else {
                    //no attachment (already deduct, add dummy 1 since it has been credited to user)
                    return Response()->json([
                        "success"           => true,   
                        "message"           => "Member has " . $memberInfo->membership . " Membership",
                        "membership"        => $memberInfo->membership,
                        "credits"           => $credits,
                        "deposit"           => $deposit,
                        "wordPointDeduction" => $wordPointDeduction,
                        "totalPointsLeft" => 1
                    ]);  
                }
            }
        }   

        

    }


    public function sendReloadEmail(Request $request, Member $member) 
    {        
        try {
        
            //send the authenticated user the email, since the Authenticated user
            $user = User::find($request->memberID);

            //E-Mail Template
            $emailTemplate = 'emails.writing.sendMemberReloadReminder';           

            //E-Mail Recipient
            $emailTo['name'] = $user->firstname ." ". $user->lastname;
            $emailTo['email'] = $user->email; 

            //Email Reply To
            $emailFrom['name']   = Config::get('mail.from.name');
            $emailFrom['email']  = Config::get('mail.from.address');

            $emailSubject = '矯正サービス'; //Information on correction service reception
            $emailMessage = '';

            $job = new \App\Jobs\SendAutoReplyJob($emailTo, $emailFrom, $emailSubject, $emailMessage, $emailTemplate);
            dispatch($job);                    

            return Response()->json([
                "success"  => true,   
                "message"  => "E-Mail Sent"
            ]); 

         } catch (\Exception $e) {

            return Response()->json([
                "success"  => false,   
                "message"  => "Error while E-Mail Sent ". $e>getMessage()
            ]); 
         }
 
    
    }
}