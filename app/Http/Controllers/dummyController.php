<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Schema as Schema;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


use App\Models\Folder;
use App\Models\File;
use App\Models\User;
use App\Models\Member;
use App\Models\Shift;
use App\Models\Tutor;
use App\Models\MemberAttribute;
use App\Models\ScheduleItem;
use App\Mail\SendEmailDemo;
use App\Models\MemoReply;
use App\Models\Questionnaire;
use App\Models\QuestionnaireItem;
use App\Models\WritingEntries;

use App\Models\TimeManager;
use App\Models\TimeManagerProgress;

use App\Models\MiniTestResult;
use App\Models\ChatSupportHistory;


use App;
use Gate;
use DB;
use Auth;
use Config;
use Mail;
use DateTime;

use App\Models\LessonMailer;

use App\Models\MiniTestAnswerKey;



use App\Mail\CustomerSupport as CustomerSupportMail;
use App\Models\PhpSpreadsheetFontStyle as Style;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Carbon\Carbon;

use App\Jobs\SendAutoReplyJob;

use App\Models\LessonHistory;
use App\Models\LessonSlideHistory;
use App\Models\MemberSelectedLessonSlideMaterial;
use App\Models\CustomTutorLessonMaterials;
use App\Models\ReportCard;

use App\Models\Homework;


class dummyController extends Controller
{

    public function __construct()
    {
    }

    public function index(Request $request, Folder $folder, $memberID = 20372) {


        $recentLessonHistory   = $folder->getRecentLessonHistory($memberID, "COMPLETED");

        echo "<pre>";
        echo "recent folder id : ". ($recentLessonHistory->folder_id ."  " .$recentLessonHistory);
        echo "<pre>";


        $nextFolderID = $folder->getNextFolderID($memberID);

        echo "user next folder ID ". $nextFolderID;

        echo "<BR>";
        echo "<BR>";
        
        if (isset($request->id)) { 
            $currentFolder = $folder->getCurrentFolder($request->id);
        } else {
            $currentFolder = $folder->getFirstRootFolder(); //get the first folder from the root folder which has 0
        }

       
        
       // echo $folder->getURLTitles($currentFolder->id);
        echo "<BR>";
        echo "searching : " . $currentFolder->id ." - " . $currentFolder->folder_name;
        echo "<BR>";
        echo "parent id : " . $currentFolder->parent_id;
        echo "<BR>";
      
        $folderId = $currentFolder->id;

   
        $nextSubFolderWithFiles = $folder->findNextSubFolderWithFiles($currentFolder);     
        $nextFolderWithFiles = $folder->findNextFolderWithFiles($currentFolder);

         if ($nextSubFolderWithFiles) {
            $folderId = $nextSubFolderWithFiles->id;
            $folderName = $nextSubFolderWithFiles->folder_name;
            
            echo "============================================<br>";
            echo "          SUBFOLDER                         <br>";
            echo "============================================<br>";
            echo  $folderId . " ". $folderName;        
        } else if ($nextFolderWithFiles) {

            $folderId = $nextFolderWithFiles->id;
            $folderName = $nextFolderWithFiles->folder_name;
        
            echo "============================================<br>";
            echo "          NEXT FOLDER                       <br>";
            echo "============================================<br>";


            echo  $folderId . " " . $folderName; 

          

          
        } else {

          
            echo "============================================<br>";
            echo "          FIND PARENT FOLDERS               <br>";
            echo "============================================<br>";
            $flattenedArray  = $folder->flattenFolderStructureWithFiles();
            $next =  $folder->findNextIDWithFiles($flattenedArray, $folderId);
            

            if (isset($next->id) && $next !== null) {

              

                echo "<p>The next ID after $folderId is $next->id with $next->folder_name.</p>";

                //echo $folder->getURLTitles($next->id);

            } else {
                echo "No next ID found after $folderId.";
            }            
        } 
       

     
    }

    
    public function latestReportCard($request, ReportCard $reportCard) {

        $latestReportCard = $reportCard->getLatest($request->id);

        echo "<pre>";
        print_r ($latestReportCard);
        echo "</pre>";


    }

    public function phpinfo(Request $request, Member $member, ScheduleItem $scheduleItem) {

        phpinfo();
    }



    public function test_activeschedules(Request $request, Member $member, ScheduleItem $scheduleItem) {
    
        $memberID =  $request->memberID;

        $memberInfo = $member->where('user_id', $memberID)->first();

        $firstSchedule = $scheduleItem->getFirstActiveSchedule($memberInfo);
        $isValid = $scheduleItem->isMemberValidToUpdate($memberInfo);        

        if ($isValid == true) {         
            echo "valid";
        } else {        
            echo "not valid";
        }    
    }

    public function testform()
    {
        return view('test/test-form');
    }    

    public function processForm(Request $request)
    {
        // Get the POST data from the form
        $postData = $request->input('data');

        // You can perform any testing or logging of the POST data here
        // For example, you can check the size of the data:
        $dataSizeInBytes = strlen($postData);

        // Convert bytes to kilobytes (KB)
        $dataSizeInKB = $dataSizeInBytes / 1024;

        // Optionally, you can log the data size
        // \Log::info("Data size: $dataSize");

        return redirect('/test-form')->with('success', "Form submitted successfully with $dataSizeInBytes : $dataSizeInKB ");
    }


    public function getParentFolders(Request $request, Folder $folder) {

        $folders = $folder->getParentFolders($request->id);

        foreach($folders as $folder) {
        
            echo $folder->id . " : " . $folder->folder_name;

            echo "<BR>";
        }
    }

    public function deleteFile(Request $request) {
    
    
        $filename = '1687774925_Screenshot_from_2023-06-23_19-53-04.png';


        if (Storage::disk('thumbnails')->exists($filename)) {
            Storage::disk('thumbnails')->delete($filename);
            return "File deleted.";
        } else {
            return "File not found.";
        }

    }


    public function NoRatings(Request $request, LessonHistory $lessonHistory, ScheduleItem $scheduleItem) {

        $noLessonRatings = $lessonHistory->getMemberLessonsWithNoRatings(Auth::user()->id);

        foreach ($noLessonRatings as $noLessonRating) {
        
            echo "tutor ID ". $noLessonRating->tutor_id ." ";

             echo " | ";

            $duration= $scheduleItem->getLessonTimeDuration($noLessonRating->schedule_id);

          
            echo "Lesson ID :" . $noLessonRating->schedule_id;

            echo " | ";

               echo $noLessonRating->duration->startTime ." - " . $noLessonRating->duration->endTime;
            
         

            echo "<BR>";
        }

       
    }

    public function getCompletedConsecutiveLessons(Request $request, ScheduleItem $scheduleItem) {

   

        $lessons             = $scheduleItem->getCompletedConsecutiveLessons($request->id);
        $consecutiveDuration = $scheduleItem->getConsecutiveLessonDuration($lessons);

        echo "<pre>";
        print_r ($lessons);
        echo "</pre>";
        exit();
        
        

    
        $isLessonStarted            = true;
        $isUserAbsent               = "false";
        $isLessonExpired            = "false";
        $isLessonExceedGracePeriod  = false;
        $gracePerionInMin           = 15; //Grace Period Extion to End Time or Session Expiration

        $startTime      = Carbon::createFromFormat('Y-m-d H:i:s', trim($request->startTime)); 
        $endTime        = Carbon::createFromFormat('Y-m-d H:i:s', trim($request->endTime));
        //$currentTime    = Carbon::now(); 
        $currentTime    =  Carbon::createFromFormat('Y-m-d H:i:s', trim($request->currentTime));

        //Format Time
        $start      = $startTime->format('Y-m-d H:i:s');
        $end        = $endTime->format('Y-m-d H:i:s');       
        $current    = $currentTime->format('Y-m-d H:i:s');
        $interval   = $currentTime->diff($start);

        //Calcuate duration in milliseconds
        $durationInMilliseconds = minutesToMilliseconds($request->duration);

        /*
        if ($startTime->format('H') === '00') {
            // Add a day when the hours is 0
            $newStarTime = $startTime->modify('+1 day');
            $start = $newStarTime->format('Y-m-d H:i:s');
        }

        if ($endTime->format('H') === '00') {
            // Add a day when the hours is 0
            $newEndTime = $endTime->modify('+1 day');
            $end = $newEndTime->format('Y-m-d H:i:s');
        }   
        */     


        //adjust grace end period
        $graceEnd   = Carbon::parse($end)->addMinutes($gracePerionInMin)->format('Y-m-d H:i:s');

        

        //Get Elapsed Time 
        $elapsedMilliseconds    = $interval->format("%f");
        $elapsedDays            = $interval->format("%a");
        $elapsedHours           = $interval->format("%h");
        $elapsedMinutes         = $interval->format("%i");
        $elapsedSeconds         = $interval->format("%s");

        //Get Total Elapsed Time in Minutes
        $totalElapsedMinutes = ($elapsedDays * 24 * 60) +
                            ($elapsedHours * 60) +
                            $elapsedMinutes;


        //Get Total Elapsed Time in Seconds
        $totalElapsedMilliseconds = ($elapsedDays * 24 * 60 * 60 * 1000) +
                           ($elapsedHours * 60 * 60 * 1000) +
                           ($elapsedMinutes * 60 * 1000) +
                           ($elapsedSeconds * 1000) +
                           $elapsedMilliseconds;


        $remaningLessonDurationInMilliseconds   = calculateRemainingMilliseconds($durationInMilliseconds, $totalElapsedMilliseconds);
        $remaningLessonDurationInMinutes        = millisecondsToMinutes($remaningLessonDurationInMilliseconds);

        if ($startTime > $currentTime) {

            //Lesson should not start since the lesson start time is so advance
            $success                = false;  
            $startTimeInvalid       = true;
            $message                = "Lesson time has not started yet";

        } else if ($isLessonStarted == false && $totalElapsedMinutes >= 15) {

            $success            = false;
            $isUserAbsent       = true;
            $message            = "User is absent, Elapsed time is 15 minutes or over";

        } else if ($isLessonStarted == true && $currentTime >= $endTime) {
        
            if ($currentTime >= $graceEnd) {

                //Lesson should not start since the lesson start time is so advance
                $success                = false;  
                $startTimeInvalid       = true;
                $isLessonExpired        = true;
               
                $message                = "Lesson time has expired";                

            } else {
            
                //Lesson should not start since the lesson start time is so advance
                $success                = true;  
                $startTimeInvalid       = true;
             
                $message                = "Lesson time has been given 15 min grace period to finish lesson";
            
            }

        } else {
        
            $success            = true;
           
            $message            = "User is present, Elapsed time is $totalElapsedMinutes, which less than 15 minutes";
            
        }       

        $test =([                    
            'success'               => $success,
            //Determin User Absent
            'isLessonStarted'       => $isLessonStarted,
            'isUserAbsent'          => $isUserAbsent,
            'isLessonExpired'       => $isLessonExpired,                        
            'message'               => $message,

            'currentDateTime'       => $current,
            'startTime'             => $start,
            'endTime'               => $end,
            'graceEnd'              => $graceEnd,
            'durationInMin'         => $request->duration,
            'durationInMs'          => $durationInMilliseconds,

            //Get Elapsed Time
            'elapsed'               => [
                                        'milliseconds'   => $elapsedMilliseconds,
                                        'days'           => $elapsedDays,
                                        'hours'          => $elapsedHours,
                                        'minutes'        => $elapsedMinutes,
                                        'seconds'        => $elapsedSeconds,
                                    ],

            //Remaining Lesson Duration
            'remaningDurationInMilliseconds'    => $remaningLessonDurationInMilliseconds,
            'remainingDurationInMinutes'        => $remaningLessonDurationInMinutes,

            //Get total Ellapsed 
            'totalElapsedMinutes'        => $totalElapsedMinutes, 
            'totalElapsedMilliseconds'   => $totalElapsedMilliseconds,
        ]); 

        echo "<pre>";
        print_r ($test);
        echo "</pre>";


    }

    public function consecutiveLessons(Request $request, Folder $folder, ScheduleItem $scheduleItem) {
    
        echo "<p>". Auth::user()->id ."</p>";
        $scheduleID = $request->id;
        $consecutiveSchedules = $scheduleItem->getCompletedConsecutiveLessons($scheduleID);
        $duration = $scheduleItem->getConsecutiveLessonDuration($consecutiveSchedules);


        echo "<pre>";
        print_r ($duration);
        echo "</pre>";
        


        echo "<pre>";
        print_r ($consecutiveSchedules);
        echo "</pre>";
    
    }


    public function getFolderPages(Request $request, Folder $folder) 
    {
        
        $id = $request->id;
        $page = $request->page;

        $itemsPerPage = 1;

        echo $page;
      
        $folders = $folder->getSubFolders($id, $page, 1);

        echo "folders<BR>";


        foreach ($folders as $folder) {
            echo $folder->id . " - " . $folder->folder_name . "<BR>" ;
        }

        echo  $folders->links();



        echo "<BR><BR>";

        echo "lessons<BR>";

        $lessons = $folder->getFolderLessons($id, $page, 1);

        echo "<pre>";
        echo "total?";
        print_r($lessons->total());

        echo "</pre>";

        foreach ($lessons as $lesson) {
            echo $lesson->id . " - " . $lesson->folder_name . "<BR>" ;
        }
       
    }

    public function selectslide(Request $request, Folder $folder) 
    {

        $memberID = 148;
        $scheduleID = $request->schedule_id;


        echo "TEST";


        $selectedMaterial = MemberSelectedLessonSlideMaterial::where('schedule_id', $scheduleID)->where('user_id', $memberID)->first();


        if ($selectedMaterial) {        
            $folderID       = $selectedMaterial->folder_id;
        } else {        
            $folderID       = $folder->getNextFolderID($memberID);
        }



        echo $folderID;

        echo $folder->getURLSegments($folderID);





     
        
        echo "<p> Schedule ID : " . $scheduleID ."</p>";
        
        $selectedMaterial = MemberSelectedLessonSlideMaterial::where('schedule_id', $scheduleID)->where('user_id', $memberID)->first();

        if (!$selectedMaterial) {

            $folder = new Folder();

            $recentLessonHistory = $folder->getRecentLessonHistory($memberID, "COMPLETED");


            echo  "LESSON HISTORY ID:: (" . $recentLessonHistory->id  .") ";

            echo "<br>";

            echo  "RECENTLY COMPLETED FOLDER ID:: (" . $recentLessonHistory->folder_id  .") ";
          
           

            $nextFolder = $folder->getNextFolder($recentLessonHistory->folder_id);

            if ($nextFolder) {

                echo "parent ? " . $nextFolder->parent_id;
                echo "<br>";
                echo "<br>";

                echo "NEXT FOLDER ID : <B> " . $nextFolder->id ."</B>"; 
                

            } else {
                
                echo  "<P>PARENT FOLDER : " . $recentLessonHistory->folder_id ."</P>";

                $nextParentFolder = $folder->getNextParentFolder($recentLessonHistory->folder_id);

                if ($nextParentFolder) {

                     dd($nextParentFolder);
                
                } else {
                

                    $nextParentFolder = $folder->getNextParentFolder($recentLessonHistory->folder_id, true);

                    if ($nextParentFolder) {
                    
                          echo "NEXT Parent folder id : " .($nextParentFolder->id);

                    } else {

                        //FIND THE INCOMPLETE OF THIS EXISTING
                        echo "no folder";

                        return null;

                    }
                
                }

               
            }

            



            echo "<pre>";
            dd ($nextFolder);
            echo "</pre>";

        
        
        } else {
        
        
            echo "folder found";
        }


        
    }


    public function testCustomLessonMaterials() {
    

        $folderID = 17;
        $scheduleID = 366;


        $files          = File::where('folder_id', $folderID)->orderBy('order_id', 'ASC')->get();
        $customFiles = CustomTutorLessonMaterials::where('folder_id', $folderID)->where('lesson_schedule_id', $scheduleID)->get();


        $mergedFilesArray = array_merge_recursive($customFiles->toArray(), $files->toArray());

   

        $mergedFiles = json_decode (json_encode ($mergedFilesArray), FALSE);



        foreach ($mergedFiles as $file) {
        
            echo "<pre>";

            print_r ($file);

            
        }


    }


    public function testchat(ChatSupportHistory $chatSupportHistory) 
    {

            $recentUsers = $chatSupportHistory->where(function($query) 
            {
                    $query->where('chatsupport_history.message_type', 'MEMBER') 
                        ->where('chatsupport_history.sender_id', '!=', 1);
                        
                            

            })->orWhere(function($query) {


                $query->where('chatsupport_history.message_type', 'CHAT_SUPPORT') 
                    ->where('chatsupport_history.recipient_id', '!=', 1);
                                                       

            })
            ->distinct()
            ->latest()
            ->get();
        
            foreach ($recentUsers as $item) {
                $ids[] = $item->sender_id;
                $ids[] = $item->recipient_id;
            }


            $uniqueUsers = array_unique($ids);  

            foreach ($uniqueUsers as $key => $recentUser) 
            {
                    echo $recentUser . "<BR>";
            }


    }


    public function users_chat(ChatSupportHistory $chatSupportHistory) 
    {
    

        $recentUsers_sender = $chatSupportHistory
                        ->select('sender_id as userid')
                        ->distinct()
                        ->where('chatsupport_history.message_type', 'MEMBER')
                        ->pluck('userid')
                        ->toArray();
                        

       $recentUsers_recipient = $chatSupportHistory
                        ->select('recipient_id as userid')
                        ->distinct()
                        ->where('chatsupport_history.message_type', 'CHAT_SUPPORT')
                        ->pluck('userid')
                        ->toArray();

        $recentUsers = array_merge($recentUsers_sender, $recentUsers_recipient); 
        $uniqueUsers = array_unique($recentUsers);
        
    
        $userList =  [];
                        
        foreach ($uniqueUsers as $key => $recentUser) 
        {
            echo $key;
            $user = User::find($recentUser);

            $userList[$key]['userid']    = $user->id;
          
        }
    }

    public function mintest() {
    

        $today = date('Y-m-d');     
        $todayDateToUpper = date('Y-m-d 23:59:59');

    
        $prevDate = date('Y-m-d',(strtotime ( "-7 day" , strtotime ($todayDateToUpper) ) ));        
        echo "PREVIOUS DATE: " . $prevDate ." - " .  $todayDateToUpper ."<BR>";



        $items = MiniTestResult::where('user_id', 148)
            ->where('valid', true)    
            ->whereBetween('time_started', array($prevDate, $todayDateToUpper))
            ->orderBy('time_started', 'ASC') 
            ->get();


        foreach($items as $item) {
            echo $item->time_started;
            echo "<BR>";

        }

    
        //$count = $miniTestResult->countPreviousResults(Auth::user()->id, 7);

        //echo $count;


    }
    public function testMinites(Request $request) {


        echo calculateMinutesToHours(120);



        $memberID = 148;

        $timeManager = TimeManager::where('member_id', $memberID)->where('valid', true)->first();
        $minutes = TimeManagerProgress::where('member_id', $memberID)->sum('total_minutes');

        //$minutes = $request->get('minutes');
       // $requriedHours = $request->get('requriedHours');

        //get required hour
        echo "Required Hours: " . $timeManager->required_hours;
        echo "<BR>";


        $requiredMinutes = calculateHoursToMinutes($timeManager->required_hours);
        echo "Required Minutes : " .  $requiredMinutes;


    
        $minutesLeft = $requiredMinutes - $minutes;

        $percentageLeft = ($minutes / $requiredMinutes) * 100;
        $formatted_percentage= number_format($percentageLeft, 2, '.', '');


        echo "<BR>";
        echo "Percnetage: " . $formatted_percentage;
        

        echo "<BR>";

        echo "Minutes Left : " . $minutesLeft ;

        echo "<BR>";

        echo minutesFormatter($minutesLeft);    
    }

    public function component_test() 
    {
        return view("dummy/index", ['title'=> "TEST"]);
    }


    public function test_entries($memberID  = 21402 ) 
    {

        //start date
        $startDate = date('Y-m-d H:i:s', strtotime(date('Y-m-1 09:00:00')));

        //temporary end date since we need to get 12:30 which is the next date
        $tempEndDate = date("Y-m-t H:i:s", strtotime($startDate));
        $endDateNextDay = date("Y-m-d", strtotime($tempEndDate . " + 1 day"));

        //final end date
        $endDate = $endDateNextDay . " 00:30:00";

        $reserved = ScheduleItem::where('member_id', $memberID)
                    ->whereBetween('lesson_time', [$startDate, $endDate])
                    ->where('schedule_status', '=', "CLIENT_RESERVED")
                    //->where('valid', 1)
                    ->count();                    

        
        $reserved_b = ScheduleItem::where('member_id', $memberID)
                    ->whereBetween('lesson_time', [$startDate, $endDate])
                    ->where('schedule_status', '=', "CLIENT_RESERVED_B")                       
                    //->where('valid', 1)
                    ->count();                    
                    
        $completed = ScheduleItem::where('member_id', $memberID)
                        ->whereBetween('lesson_time', [$startDate, $endDate])
                        ->where('schedule_status', '=', "COMPLETED")                       
                        //->where('valid', 1)
                        ->count();

        $not_available = ScheduleItem::where('member_id', $memberID)
                        ->whereBetween('lesson_time', [$startDate, $endDate])
                        ->where('schedule_status', '=', "CLIENT_NOT_AVAILABLE")                       
                        //->where('valid', 1)
                        ->count();

      
        $writingPoints = WritingEntries::where('user_id', $memberID)->where('type', 'Monthly')->sum('total_points');


        $reserveCount = $reserved + $reserved_b + $completed + $not_available + $writingPoints ;


        echo "total points : ". $reserveCount ."<BR>";

        echo "<BR>----<br>";

        $all_entries = WritingEntries::where('user_id', $memberID)->get();
        foreach ($all_entries as $all) {        
            $test  = json_decode($all->value, true);
            echo "<pre>";
            print_r ($test);
            echo "</pre>";       
        }        
    }





    public function getTotalreserved (ScheduleItem $scheduleItemObj) 
    {
        $memberID = 4;


        //TOTAL RESERVED
        
        //$totalReserved =  $scheduleItemObj->getTotalLessonReserved($memberID, '11', '01'); 


        //start date
        $startDate = date('Y-m-d H:i:s', strtotime(date("2021-11-01 09:00:00")));

        //temporary end date since we need to get 12:30 which is the next date
        $tempEndDate = date("Y-m-t H:i:s", strtotime($startDate));
        $endDateNextDay = date("Y-m-d", strtotime($tempEndDate . " + 1 day"));

        //final end date
        $endDate = $endDateNextDay . " 00:30:00";
        
        
        $reserved = ScheduleItem::where('member_id', $memberID)
                    ->whereBetween('lesson_time', [$startDate, $endDate])
                    ->where('schedule_status', '=', "CLIENT_RESERVED")  
                    ->get();
                    
        echo "<Br>";
        echo $startDate . " - " . $endDate;
        echo "<Br>";                    
        echo "<pre>";
        foreach ($reserved as $item) {
            echo $item->lesson_time;
            echo "<BR>";
        }        
        echo "</pre>";

       
        
        echo $scheduleItemObj->getTotalLessonForCurrentMonth($memberID); 

        echo " - ";

        echo $scheduleItemObj->getTotalReservedForCurrentMonth($memberID);


        //start date
        $startDate = date('Y-m-d H:i:s', strtotime(date('Y-m-01 09:00:00')));

        //temporary end date since we need to get 12:30 which is the next date
        $tempEndDate = date("Y-m-t H:i:s", strtotime($startDate));
        $endDateNextDay = date("Y-m-d", strtotime($tempEndDate . " + 1 day"));

        //final end date
        $endDate = $endDateNextDay . " 00:30:00";

        echo "<Br>";
        echo $startDate . " - " . $endDate;
        echo "<Br>";

        $reserved = ScheduleItem::where('member_id', $memberID)
                    ->whereBetween('lesson_time', [$startDate, $endDate])
                    ->where('schedule_status', '=', "CLIENT_RESERVED")
                    ->get();

        echo "<pre>";
        foreach ($reserved as $item) {
            echo $item->lesson_time;
            echo "<BR>";
        }        
        echo "</pre>";


        /* test old code (buggy)*/
        $currentYear = date('Y');
        $currentMonth = date('m');
                
        $reserved = ScheduleItem::where('member_id', $memberID)
                    ->whereYear('lesson_time', '=', $currentYear)
                    ->whereMonth('lesson_time','=', $currentMonth)
                    ->where('schedule_status', '=', "CLIENT_RESERVED")                       
                    //->where('valid', 1)
                    ->get();


            echo "<pre>";
            foreach ($reserved as $item) {
                echo $item->lesson_time;
                echo "<BR>";
            }        
            echo "</pre>";                    

    }

    public function datesort() 
    {    

        $userid = 148;        
        $items = DB::table("member_attribute")->where('member_id', $userid)->get();          

       foreach ($items as $item) 
       {
         $results[] = array(
                            'attribute' => $item->attribute,
                            'month' => $item->month,
                            'year' => $item->year,
                            'date' => $item->year ."-". date("m", strtotime($item->month)) ."-01",
                        );
       }
       usort($results, sortByDate('date'));
       $reversed_results = array_reverse($results);

        foreach ($reversed_results as $item ) {
            //echo $item['updated_at'] ."<BR>";


            echo date('d/M/Y', strtotime($item['date'] ));

            echo "<BR>";
        }
    

    }
    
    public function mailDateFormat(ScheduleItem $scheduleItem) {

        $scheduleItem = $scheduleItem->find(912532);

        echo ESIMailDateTimeFormat($scheduleItem->lesson_time);

        //return view("dummy/index", ['title'=> "TEST"]);

    }

    public function dropzone() {
        return view("dummy/dropzoneSimple", ['title'=> "TEST"]);
    }

    public function simpleuploader() {
        return view("dummy/index", ['title'=> "TEST"]);
    }

    public function updateQuestionnaire() {
        $questions = Questionnaire::where('created_at', '>=', date('2021-05-10'))->get();
        foreach ($questions as $q) 
        {
            echo "===============================<BR>";
            echo $q->id .  " - " . $q->created_at . "<BR>";
            echo "===============================<BR>";
            
            $items = QuestionnaireItem::where('questionnaire_id', $q->id)->where('question', "")->where('valid', true)->get();
            $ctr = 1;

            foreach ($items as $item) {

                echo $item->id ."<BR>";

                $data = [
                            'question' => 'QUESTION_'.$ctr
                ];
                

                $item->update($data);               

                $ctr ++;
               
            }
        }
        
    }

    public function memoReply() 
    {

        $date = date('Y-m-d H:i:s');

        $schedules = MemoReply::where('schedule_item.tutor_id', 21809)
        ->join('schedule_item', 'schedule_item.id', '=', 'memo_replies.schedule_item_id')
        ->groupBy('memo_replies.schedule_item_id')
        ->where(function ($q) {                
            $q->orWhere('schedule_status', 'CLIENT_RESERVED')
            ->orWhere('schedule_status', 'CLIENT_RESERVED_B');
        })         
        ->select('memo_replies.id', 'memo_replies.schedule_item_id', 'memo_replies.updated_at', 'memo_replies.message')
        ->where('schedule_item.lesson_time', ">=", $date)
        ->get();

        print_r ($schedules);
        
        foreach ($schedules as $schedule) 
        {
           $latestReply = MemoReply::where('schedule_item_id', $schedule->id)->where('is_read', false)->where('message_type', "MEMBER")->orderBy('updated_at', 'DESC')->first();           
            
           $results[] = array(
                            'schedule_id' => $latestReply->schedule_item_id, 
                            'message' => $latestReply->message,
                            'updated_at' => $latestReply->updated_at ,
                        );
        }               
        usort($results, sortByDate('updated_at'));

        echo "<pre>";

        $object = (object) $results;

        print_R ($object);
        
        
    }
  
    public function index2() {
        $date = date('Y-m-d H:i:s');
        
       //get schedule unique
       /*
        $schedules = MemoReply::where('schedule_item.tutor_id', 21809)
                ->join('schedule_item', 'schedule_item.id', '=', 'memo_replies.schedule_item_id')
                ->groupBy('memo_replies.schedule_item_id')
                ->where(function ($q) {                
                    $q->orWhere('schedule_status', 'CLIENT_RESERVED')
                    ->orWhere('schedule_status', 'CLIENT_RESERVED_B');
                })         
                ->select('memo_replies.id', 'memo_replies.schedule_item_id', 'memo_replies.updated_at', 'memo_replies.message')
                ->where('schedule_item.lesson_time', ">=", $date)
                ->get();

        */

        $schedules = ScheduleItem::where('tutor_id', 21809)->where('valid', 1)->where(function ($q) {                
            $q->orWhere('schedule_status', 'CLIENT_RESERVED')
            ->orWhere('schedule_status', 'CLIENT_RESERVED_B');
        })->where('lesson_time', ">=", $date)
        ->orderby('lesson_time', 'ASC')
        ->get();        

                
        foreach ($schedules as $schedule) 
        {
           $latestReply = MemoReply::where('schedule_item_id', $schedule->id)->where('is_read', false)->where('message_type', "MEMBER")->orderBy('updated_at', 'DESC')->first();           
            
           $results[] = array(
                            'schedule_id' => $latestReply->schedule_item_id, 
                            'message' => $latestReply->message,
                            'updated_at' => $latestReply->updated_at ,
                        );
        }               
        usort($results, sortByDate('updated_at'));

        echo "<pre>";

        $object = (object) $results;

        print_R ($object);
    }

    public function uploader(){
        
       
        return view("dummy/index", ['title'=> "TEST"]);

    }
    

    public function sendTestMail() {

        //$user['email'] = 'emailroy2002@yahoo.com';
        //Mail::to($user['email'])->send(new SendEmailDemo());        
        
        Mail::to("bhadz.trex@gmail.com")
        ->cc(["emailroy2002@yahoo.com", "abellana@gmail.com"])
        //->bcc($evenMoreUsers)
        //->from('support@mytutor.co.jp', 'マイチューター')        
        ->send(new SendEmailDemo());
    }

    public function testDispatch() {

        $details['to'] = 'abellana@gmail.com';
        $details['name'] = 'Roy this is a test dispatch';
        $details['subject'] = 'Hello roy i am testing this';
        $details['message'] = 'Here goes all message body.';

        SendMailJob::dispatch($details);

        return response('Email sent successfully');

    }

    public function testMailReservedB(LessonMailer $lessonMailer) {
        
        //*** TEMPLATE ***/
        $details['template'] = "emails.client.reserved";        

        //email to:
        $details['email'] = 'emailroy2002@yahoo.com';
        $details['subject'] = 'マイチューター：レッスン予約のご案内'; //reserved
        
        //initialize member, tutor and schedule items
        $memberObj = new Member();
        $tutorObj = new Tutor();
        $scheduleItem = new scheduleItem();

        $member = $memberObj->where('user_id',20372)->first();
        $tutor = $tutorObj->where('user_id', 14253)->first();
        $scheduleItem = $scheduleItem->find(879884);

        $lessonMailer->sendMemberEmail($member, $tutor, $scheduleItem);

        return view($details['template'], compact('member','tutor', 'scheduleItem'));

    }

    public function testemailWriting() 
    {
        
        $user = Auth::user();

        //E-Mail Recipient
        $emailTo['name'] = $user->firstname ." ". $user->lastname;
        $emailTo['email'] = $user->email; 

        //Email Reply To
        $emailFrom['name']   = Config::get('mail.from.name');
        $emailFrom['email']  = Config::get('mail.from.address');

        
        $subject = "test is a localhost test";
        $message = "this is a test message";

        //set template
        $template = 'emails.writing.autoreply';

        try {

            $job = new \App\Jobs\SendAutoReplyJob($emailTo, $emailFrom, $subject, $message, $template);
            dispatch($job);

        } catch (Throwable $e) {
            report($e);
    
            return false;
        }        


    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function testMailReserved()
    {         
        /*
        if (App::environment(['local', 'staging'])) {
            echo "1";
        } else {
            echo "2";
        }*/



        //*** TEMPLATE ***/
        $details['template'] = "emails.tutor.tutorNotifyCancelled";

        //email to:
        $details['email'] = 'emailroy2002@yahoo.com';
        $details['subject'] = 'マイチューター：レッスン予約のご案内'; //reserved
        
        //initialize member, tutor and schedule items
        $memberObj = new Member();
        $tutorObj = new Tutor();
        $scheduleItem = new scheduleItem();

        $member = $memberObj->where('user_id',20372)->first();
        $tutor = $tutorObj->where('user_id', 14253)->first();
        $scheduleItem = $scheduleItem->find(879884);

        return view('emails.tutor.tutorNotifyCancelled', compact('member','tutor', 'scheduleItem'));



        $job = new \App\Jobs\SendEmailJob($details, $member, $tutor, $scheduleItem);
        dispatch($job);        


        dd('Send Email Successfully');
        //return view('emails.lesson.reserved', compact('member','tutor', 'scheduleItem'));
    }


    public function testMailCancelled()
    {        
        
        //*** TEMPLATE ***/
        //$details['template'] = "emails.lesson.memberNotifyCancelled";


        $details['template'] = "emails.manager.clientReserved";

        //email to:
        $details['email'] = 'emailroy2002@yahoo.com';
        $details['subject'] = 'マイチューター：レッスンキャンセルのご案内'; //cancelled
        
        //initialize member, tutor and schedule items
        $memberObj = new Member();
        $tutorObj = new Tutor();
        $scheduleItem = new scheduleItem();

        $member = $memberObj->where('user_id',20372)->first();
        $tutor = $tutorObj->where('user_id', 14253)->first();
        $scheduleItem = $scheduleItem->find(879884);

        //$job = new \App\Jobs\SendEmailJob($details, $member, $tutor, $scheduleItem);
        //dispatch($job);        
        //dispatch(new \App\Jobs\SendEmailJob($details, $member, $tutor, $scheduleItem));        


        $lessonMailer = new LessonMailer();
        $lessonMailer->send($member, $tutor, $scheduleItem);      
        
        
        dd('Send Email Successfully');        
    }    


    public function clientNotAvailable() 
    {  
        $details['template'] = "emails.lesson.cancelled";

        //email to:
        $details['email'] = 'emailroy2002@yahoo.com';
        $details['subject'] = 'マイチューター：レッスン欠席のご案内'; //client not available
        
        //initialize member, tutor and schedule items
        $memberObj = new Member();
        $tutorObj = new Tutor();
        $scheduleItem = new scheduleItem();

        $member = $memberObj->where('user_id',20372)->first();
        $tutor = $tutorObj->where('user_id', 14253)->first();
        $scheduleItem = $scheduleItem->find(879884);

        $job = new \App\Jobs\SendEmailJob($details, $member, $tutor, $scheduleItem);

        dispatch($job);        
        dd('Send Email Successfully');         
    }


    public function tutorNotAvailable() 
    {    
        //マイチューター：レッスンキャンセルのご案内 (CHECKED)


        $details['template'] = "emails.lesson.cancelled";

        //email to:
        $details['email'] = 'emailroy2002@yahoo.com';
        $details['subject'] = 'マイチューター：レッスンキャンセルのご案内'; //tutor not available
        
        //initialize member, tutor and schedule items
        $memberObj = new Member();
        $tutorObj = new Tutor();
        $scheduleItem = new scheduleItem();

        $member = $memberObj->where('user_id',20372)->first();
        $tutor = $tutorObj->where('user_id', 14253)->first();
        $scheduleItem = $scheduleItem->find(879884);

        $job = new \App\Jobs\SendEmailJob($details, $member, $tutor, $scheduleItem);

        dispatch($job);        
        dd('Send Email Successfully');          

    }



    function lessonLimitTest() 
    {      
        $memberID = Auth::user()->id;
        $memberAttribute = new MemberAttribute();
        $scheduleItem = new ScheduleItem();
        $attribute = $memberAttribute->getLessonLimit($memberID);

        //current lesson limit
        $limit = $attribute->lesson_limit;
        //total schedule added (active)
        $currentMonthTotalReserves = $scheduleItem->getTotalLessonForCurrentMonth($memberID);

        echo $currentMonthTotalReserves;

        if ($currentMonthTotalReserves >= $limit) {
            echo "over the total";
        } else {
            echo "okay";
        }

    }


    public function testUserPoints($memberID) {
        //18153 - Kobayashi, Ryusei  

    }

    public function testExpiry(Member $member) 
    {
        $user_id =  Auth::user()->id;        
    
        $memberInfo = $member->where('user_id', 20372)->first();


        $today = date("Y-m-d, H:i");
        $expiry = date("Y-m-d, 00:30", strtotime($memberInfo->credits_expiration ." + 1 day"));;

        echo $today ." > ". $expiry;


        
        echo "<bR>";

        
        if ($today > $expiry) {
            echo "hala expired<BR>";
        } else {
            echo "hala dili<BR>";
        }

        echo "<bR>";

        if ($member->isMemberCreditExpired(20372)) {
            echo "<p>expired</p>";
        } else {
            echo "not expired";
        }


    }


    public function testGetMembers(Request $request) 
    {
        $today = Carbon::now();
        $dateFrom = $request->get('from');
        $dateTo = $request->get('to');

        //get query with expiration null
        $memberQuery = Member::join('agent_transaction', 'agent_transaction.member_id', '=', 'members.user_id');
        $memberQuery = $memberQuery->whereBetween('agent_transaction.created_at', array($dateFrom, $dateTo));
        $memberQuery = $memberQuery->where('agent_transaction.transaction_type', "LIKE", "EXPIRED");
        $memberQuery = $memberQuery->where('members.membership', "Point Balance");
        $memberQuery = $memberQuery->where('members.credits_expiration', null);  //expired
        $memberQuery = $memberQuery->groupby('members.user_id')->get()->toArray();

        

        $memberQueryOne = Member::join('users', 'users.id', '=', 'members.user_id');
        $memberQueryOne = $memberQueryOne->select("members.*", "users.id", "users.email", "users.firstname", 'users.lastname', DB::raw("CONCAT(users.firstname,' ',users.lastname) as fullname"));
        $memberQueryOne = $memberQueryOne->whereBetween(DB::raw('DATE(members.credits_expiration)'), array($dateFrom, $dateTo));
        $memberQueryOne = $memberQueryOne->where('members.credits_expiration', ">=", $dateFrom);
        $memberQueryOne = $memberQueryOne->whereDate('members.credits_expiration', '<=', $dateTo);
        $memberQueryOne = $memberQueryOne->where('membership', "Point Balance");        
        $memberQueryOne = $memberQueryOne->orderby('members.credits_expiration', 'ASC')->get()->toArray();

        $memberQuery = array_merge($memberQuery, $memberQueryOne);



        //get query with expiration null
        $memberQueryThree = Member::join('agent_transaction', 'agent_transaction.member_id', '=', 'members.user_id');
        $memberQueryThree = $memberQueryThree->whereBetween('members.credits_expiration', array($dateFrom, $dateTo));
        $memberQueryThree = $memberQueryThree->where('members.membership', "Point Balance");
        $memberQueryThree = $memberQueryThree->groupby('members.user_id')->get()->toArray();

        $memberQueryAll = array_merge($memberQuery, $memberQueryThree);

        $memberQueryAll = unique_multidim_array($memberQueryAll, 'user_id');


        foreach ($memberQueryAll as $memberItem) {
            $member = Member::where('user_id', $memberItem['user_id'])->first();
            echo $member->user->id ." " .$member->user->firstname . " " . $member->user->lastname . "  Status: " .  $member->transaction_type . " | expiry:  " . $member->credits_expiration
             ." | Expired Added :  ". date('M-d-y', strtotime($memberItem['created_at']));
            echo "<BR>";
        }
        
        
        /*
        $members =  DB::table('members')->join('users', 'users.id', '=', 'members.user_id')
        ->select('members.user_id', 'members.nickname')
        ->where('users.valid', 1)
        ->get();

        return view('admin.test.index');
        */

    }
    
    

    function excelExportTest() 
    {
        $dateToday = date("m/d/Y");
        $filename =  "MyPageSortedMemberList.xlsx";
               
        $spreadsheet = Style::init();
        $sheet = $spreadsheet->getActiveSheet(); 

        //Set Header Text
        $sheet->setCellValue('B1', "Sorted Member List as of $dateToday");     
       
        //Secondary Field Headers (h2)
        $sheet->setCellValue('B2', "I.D");
        $sheet->setCellValue('C2', "First Name");
        $sheet->setCellValue('D2', "Last Name");
        $sheet->setCellValue('E2', "E-Mail");
        $sheet->setCellValue('F2', "Credits");
        $sheet->setCellValue('G2', "Expiration Date");
        
        //style for field headers h2
        $styleArrayH2 = Style::setHeader('FFFFFF','669999', 20);
        $spreadsheet->getActiveSheet()->getStyle('B2:G2')->applyFromArray($styleArrayH2);        

        //SET COLOR MANUAL
        //$spreadsheet->getActiveSheet()->getStyle('B2:G2')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLUE);


        $writer = new Xlsx($spreadsheet);
        $writer->save($filename);


        if(file_exists($filename)) {
            header('Content-Description: File Transfer');
            header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
            header('Content-Disposition: attachment; filename="'.basename($filename).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filename));
            flush(); // Flush system output buffer
            readfile($filename);
            die();
        } else {
            http_response_code(404);
	        die();
        }        

    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $memberQuery = Member::join('users', 'users.id', '=', 'members.user_id');
        $memberQuery = $memberQuery->select("members.*", "users.id", "users.email", "users.firstname", 'users.lastname', DB::raw("CONCAT(users.firstname,' ',users.lastname) as fullname"));
        $memberQuery = $memberQuery->whereBetween(DB::raw('DATE(members.credits_expiration)'), array($dateFrom, $dateTo));

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
