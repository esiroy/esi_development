<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LessonMailer extends Model
{

    public function __construct()
    { 

    }
    
    
    //@data: [Email, subject, View Template]
    public function send($memberInfo, $tutorInfo, $scheduleItem) 
    {
        $scheduleItemObj = new scheduleItem();
        $selectedSchedule = $scheduleItemObj->find($scheduleItem->id);

     
        if ( $selectedSchedule->schedule_status == 'CLIENT_RESERVED' ||  $selectedSchedule->schedule_status == 'CLIENT_RESERVED_B')  {
            /*******************************************
            *       SEND MAIL TO MEMBER 
            *******************************************/                
            $memberEmailData['template'] = "emails.lesson.memberNotifyReserved";
            $memberEmailData['subject'] = 'マイチューター：レッスン予約のご案内'; //My Tutor: Lesson Reservations
            $memberEmailData['email'] = $memberInfo->user->email; //recipient (mailto:)

            self::dispatchEmail($memberEmailData, $memberInfo, $tutorInfo, $selectedSchedule);


            /*******************************************
            *       SEND MAIL TO TUTOR 
            *******************************************/
            $tutorEmaildata['template'] = "emails.lesson.tutorNotifyReserved";                                
            $tutorEmaildata['subject'] = 'My Tutor: Lesson Schedule Reserved'; //reserved
            $tutorEmaildata['email'] =  $tutorInfo->user->email; //recipient (mailto:)            
    
            self::dispatchEmail($tutorEmaildata, $memberInfo, $tutorInfo, $selectedSchedule); //Add to Queue 

        } else if ( $selectedSchedule->schedule_status == 'SUPPRESSED_SCHEDULE')  {


            /*******************************************
            *       SEND MAIL TO MEMBER 
            *******************************************/                
            $memberEmailData['template'] = "emails.lesson.memberNotifyReserved";
            $memberEmailData['subject'] = 'マイチューター：代講講師予約のご案内'; //My Tutor: Advance Lecture Instructor Reservation
            $memberEmailData['email'] = $memberInfo->user->email; //recipient (mailto:)

            self::dispatchEmail($memberEmailData, $memberInfo, $tutorInfo, $selectedSchedule);            

            /*******************************************
            *       SEND MAIL TO TUTOR 
            *******************************************/
            $tutorEmaildata['template'] = "emails.lesson.tutorNotifyReserved";                                
            $tutorEmaildata['subject'] = 'My Tutor: Lesson Schedule Reserved'; //reserved
            $tutorEmaildata['email'] =  $tutorInfo->user->email; //recipient (mailto:)            
    
            self::dispatchEmail($tutorEmaildata, $memberInfo, $tutorInfo, $selectedSchedule); //Add to Queue        
            

        } else if ( $selectedSchedule->schedule_status == 'TUTOR_CANCELLED')  {

            /*******************************************
            *       SEND MAIL TO MEMBER 
            *******************************************/
            $memberEmailData['template'] = "emails.lesson.memberNotifyCancelled";
            $memberEmailData['subject'] = 'マイチューター：レッスンキャンセルのご案内'; //My Tutor: Lesson Cancellation
            $memberEmailData['email']   = $memberInfo->user->email; //recipient (mailto:)

            self::dispatchEmail($memberEmailData, $memberInfo, $tutorInfo, $selectedSchedule);            


            /*******************************************
            *       SEND MAIL TO TUTOR 
            *******************************************/
            $tutorEmaildata['template'] = "emails.lesson.tutorNotifyCancelled";                                
            $tutorEmaildata['subject'] = 'My Tutor: Lesson Schedule Cancelled'; //Cancelled
            $tutorEmaildata['email'] =  $tutorInfo->user->email; //recipient (mailto:)              
    
            self::dispatchEmail($tutorEmaildata, $memberInfo, $tutorInfo, $selectedSchedule); //Add to Queue        


        } else if ( $selectedSchedule->schedule_status == 'CLIENT_NOT_AVAILABLE')  {

            /*******************************************
            *       SEND MAIL TO MEMBER 
            *******************************************/
            $memberEmailData['template'] = "emails.lesson.memberNotifyClientNotAvailable";
            $memberEmailData['subject'] = 'マイチューター：レッスン欠席のご案内'; //My Tutor: Lesson Absence
            $memberEmailData['email']   = $memberInfo->user->email; //recipient (mailto:)

            self::dispatchEmail($memberEmailData, $memberInfo, $tutorInfo, $selectedSchedule);                

            /*******************************************
            *       SEND MAIL TO TUTOR 
            *******************************************/
            $tutorEmaildata['template'] = "emails.lesson.tutorNotifyCancelled";                                
            $tutorEmaildata['subject'] = 'My Tutor: Lesson Schedule Cancelled'; //Cancelled
            $tutorEmaildata['email'] =  $tutorInfo->user->email; //recipient (mailto:)            
    
            self::dispatchEmail($tutorEmaildata, $memberInfo, $tutorInfo, $selectedSchedule); //Add to Queue                    

        } else if ( $selectedSchedule->schedule_status == 'COMPLETED')  {       
            //@note: completed does not send any message
        } 


                                
    }

    public function dispatchEmail($emailData, $memberInfo, $tutorInfo, $selectedSchedule) 
    {
        $jobTutor = new \App\Jobs\SendEmailJob($emailData, $memberInfo, $tutorInfo, $selectedSchedule);
        dispatch($jobTutor); //Add to Queue 
    }


}
