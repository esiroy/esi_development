<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LessonMailer extends Model
{

    public function __construct()
    {

    }

    public function sendMemberCancellationEmail($memberInfo, $tutorInfo, $selectedSchedule) 
    {
        /*******************************************
         *       SEND MAIL TO MEMBER
         *******************************************/
        $memberEmailData['template'] = "emails.client.cancel";
        $memberEmailData['subject'] = 'マイチューター：レッスンキャンセルのご案内'; //My Tutor: Information for canceling lessons
        $memberEmailData['email'] = $memberInfo->user->email; //recipient (mailto:)

        self::dispatchEmail($memberEmailData, $memberInfo, $tutorInfo, $selectedSchedule);

        /*******************************************
         *       SEND MAIL TO TUTOR
         *******************************************/
        $tutorEmaildata['template'] = "emails.tutor.tutorNotifyCancelled";
        $tutorEmaildata['subject'] = 'My Tutor: Lesson Schedule Cancelled'; //Cancelled
        $tutorEmaildata['email'] = $tutorInfo->user->email; //recipient (mailto:)

        self::dispatchEmail($tutorEmaildata, $memberInfo, $tutorInfo, $selectedSchedule); //Add to Queue
    }


    public function sendMemberAbsentEmail($memberInfo, $tutorInfo, $selectedSchedule) 
    {
        /*******************************************
         *       SEND MAIL TO MEMBER
         *******************************************/
        $memberEmailData['template'] = "emails.client.absent";
        $memberEmailData['subject'] = 'マイチューター：レッスン欠席のご案内'; //My Tutor: Lesson Absence
        $memberEmailData['email'] = $memberInfo->user->email; //recipient (mailto:)

        self::dispatchEmail($memberEmailData, $memberInfo, $tutorInfo, $selectedSchedule);

        /*******************************************
         *       SEND MAIL TO TUTOR
         *******************************************/
        $tutorEmaildata['template'] = "emails.tutor.tutorNotifyCancelled";
        $tutorEmaildata['subject'] = 'My Tutor: Lesson Schedule Cancelled'; //Cancelled
        $tutorEmaildata['email'] = $tutorInfo->user->email; //recipient (mailto:)

        self::dispatchEmail($tutorEmaildata, $memberInfo, $tutorInfo, $selectedSchedule); //Add to Queue
    }    



    //send client email
    public function sendMemberEmail($memberInfo, $tutorInfo, $scheduleItem)
    {
        $scheduleItemObj = new scheduleItem();
        $selectedSchedule = $scheduleItemObj->find($scheduleItem->id);

        if ($selectedSchedule->schedule_status == 'CLIENT_RESERVED') 
        {
            /*******************************************
             *       SEND MAIL TO MEMBER (FROM MEMBER AREA)
             *******************************************/      
            $memberEmailData['template'] = "emails.client.reserved";
            $memberEmailData['subject'] = 'マイチューター：レッスン予約のご案内'; //My Tutor: Lesson Reservations
            $memberEmailData['email'] = $memberInfo->user->email; //recipient (mailto:)

            $this->dispatchEmail($memberEmailData, $memberInfo, $tutorInfo, $selectedSchedule);          

            /*******************************************
             *       SEND MAIL TO TUTOR (FROM MEMBER AREA)
             *******************************************/
            $tutorEmaildata['template'] = "emails.tutor.tutorNotifyReserved";
            $tutorEmaildata['subject'] = 'My Tutor: Lesson Schedule Reserved'; //reserved
            $tutorEmaildata['email'] = $tutorInfo->user->email; //recipient (mailto:)

            $this->dispatchEmail($tutorEmaildata, $memberInfo, $tutorInfo, $selectedSchedule); //Add to Queue

        } else if ($selectedSchedule->schedule_status == 'CLIENT_RESERVED_B') {

            $memberEmailData['template'] = "emails.client.reserved_type_b";
            $memberEmailData['subject'] = 'マイチューター：レッスン予約のご案内'; //My Tutor: Lesson Reservations
            $memberEmailData['email'] = $memberInfo->user->email; //recipient (mailto:)

            self::dispatchEmail($memberEmailData, $memberInfo, $tutorInfo, $selectedSchedule);                   


            /*******************************************
             *       SEND MAIL TO TUTOR
             *******************************************/
            $tutorEmaildata['template'] = "emails.tutor.tutorNotifyReserved";
            $tutorEmaildata['subject'] = 'My Tutor: Lesson Schedule Reserved'; //reserved
            $tutorEmaildata['email'] = $tutorInfo->user->email; //recipient (mailto:)

            self::dispatchEmail($tutorEmaildata, $memberInfo, $tutorInfo, $selectedSchedule); //Add to Queue
            
            

        } else if ($selectedSchedule->schedule_status == 'CLIENT_NOT_AVAILABLE') {

            /*******************************************
             *       SEND MAIL TO MEMBER
             *******************************************/
            $memberEmailData['template'] = "emails.client.absent";
            $memberEmailData['subject'] = 'マイチューター：レッスン欠席のご案内'; //My Tutor: Lesson Absence
            $memberEmailData['email'] = $memberInfo->user->email; //recipient (mailto:)

            self::dispatchEmail($memberEmailData, $memberInfo, $tutorInfo, $selectedSchedule);

            /*******************************************
             *       SEND MAIL TO TUTOR
             *******************************************/
            $tutorEmaildata['template'] = "emails.tutor.tutorNotifyCancelled";
            $tutorEmaildata['subject'] = 'My Tutor: Lesson Schedule Cancelled'; //Cancelled
            $tutorEmaildata['email'] = $tutorInfo->user->email; //recipient (mailto:)

            self::dispatchEmail($tutorEmaildata, $memberInfo, $tutorInfo, $selectedSchedule); //Add to Queue

        }
    }    

    /* ADMIN EMAILS  HERE*/
    public function send($memberInfo, $tutorInfo, $scheduleItem)
    {
        $scheduleItemObj = new scheduleItem();
        $selectedSchedule = $scheduleItemObj->find($scheduleItem->id);

        if ($selectedSchedule->schedule_status == 'CLIENT_RESERVED') {

            /*******************************************
             *       SEND MAIL TO MEMBER - (check for "Regular reservation" or "For replacement")
             *******************************************/
            if (strtolower($selectedSchedule->email_type) == "regular reservation") {

                $memberEmailData['template'] = "emails.manager.clientReserved";
                $memberEmailData['subject'] = 'マイチューター：レッスン予約のご案内'; //My Tutor: Lesson Reservations
                $memberEmailData['email'] = $memberInfo->user->email; //recipient (mailto:)

                self::dispatchEmail($memberEmailData, $memberInfo, $tutorInfo, $selectedSchedule);

            } else if (strtolower($selectedSchedule->email_type) == "for replacement") {

                $memberEmailData['template'] = "emails.manager.clientReservedForReplacement";
                $memberEmailData['subject'] = 'マイチューター：レッスン予約のご案内'; //My Tutor: Lesson Reservations
                $memberEmailData['email'] = $memberInfo->user->email; //recipient (mailto:)
    
                self::dispatchEmail($memberEmailData, $memberInfo, $tutorInfo, $selectedSchedule);                
            }

            /*******************************************
             *       SEND MAIL TO TUTOR
             *******************************************/
            $tutorEmaildata['template'] = "emails.tutor.tutorNotifyReserved";
            $tutorEmaildata['subject'] = 'My Tutor: Lesson Schedule Reserved'; //reserved
            $tutorEmaildata['email'] = $tutorInfo->user->email; //recipient (mailto:)

            self::dispatchEmail($tutorEmaildata, $memberInfo, $tutorInfo, $selectedSchedule); //Add to Queue

        } else if ($selectedSchedule->schedule_status == 'CLIENT_RESERVED_B') {

            /*******************************************
             *       SEND MAIL TO MEMBER  (check for "Regular reservation" or "For replacement")
            *******************************************/

            if (strtolower($selectedSchedule->email_type) == "regular reservation") {

                $memberEmailData['template'] = "emails.manager.clientReserved_b";
                $memberEmailData['subject'] = 'マイチューター：レッスン予約のご案内'; //My Tutor: Lesson Reservations
                $memberEmailData['email'] = $memberInfo->user->email; //recipient (mailto:)
    
                self::dispatchEmail($memberEmailData, $memberInfo, $tutorInfo, $selectedSchedule);                

            } else if (strtolower($selectedSchedule->email_type) == "for replacement") {

                $memberEmailData['template'] = "emails.manager.clientReserved_b_ForReplacement";
                $memberEmailData['subject'] = 'マイチューター：レッスン予約のご案内'; //My Tutor: Lesson Reservations
                $memberEmailData['email'] = $memberInfo->user->email; //recipient (mailto:)
    
                self::dispatchEmail($memberEmailData, $memberInfo, $tutorInfo, $selectedSchedule);
            }



            /*******************************************
             *       SEND MAIL TO TUTOR
             *******************************************/
            $tutorEmaildata['template'] = "emails.tutor.tutorNotifyReserved";
            $tutorEmaildata['subject'] = 'My Tutor: Lesson Schedule Reserved'; //reserved
            $tutorEmaildata['email'] = $tutorInfo->user->email; //recipient (mailto:)

            self::dispatchEmail($tutorEmaildata, $memberInfo, $tutorInfo, $selectedSchedule); //Add to Queue

        } else if ($selectedSchedule->schedule_status == 'SUPPRESSED_SCHEDULE') {

            /*******************************************
             * NO EMAIL, JUST RESERVED IT FOR NO ONE (BLOCKED, SUPPRESSED, NOT AVAILBLE)
             *******************************************/

        } else if ($selectedSchedule->schedule_status == 'TUTOR_CANCELLED') {

            if (strtolower($selectedSchedule->email_type) == "cancel with replacement") {
                $memberEmailData['template'] = "emails.manager.tutorCancelwithReplacement";
            } else {
                $memberEmailData['template'] = "emails.manager.tutorcancel";
            }

            /*******************************************
             *       SEND MAIL TO MEMBER
             *******************************************/
            $memberEmailData['subject'] = 'マイチューター：レッスンキャンセルのご案内'; //My Tutor: Lesson Cancellation
            $memberEmailData['email'] = $memberInfo->user->email; //recipient (mailto:)
            self::dispatchEmail($memberEmailData, $memberInfo, $tutorInfo, $selectedSchedule);

            /*******************************************
             *       SEND MAIL TO TUTOR
             *******************************************/
            $tutorEmaildata['template'] = "emails.tutor.tutorNotifyCancelled";
            $tutorEmaildata['subject'] = 'My Tutor: Lesson Schedule Cancelled'; //Cancelled
            $tutorEmaildata['email'] = $tutorInfo->user->email; //recipient (mailto:)

            self::dispatchEmail($tutorEmaildata, $memberInfo, $tutorInfo, $selectedSchedule); //Add to Queue

        } else if ($selectedSchedule->schedule_status == 'CLIENT_NOT_AVAILABLE') {

            /*******************************************
             *       SEND MAIL TO MEMBER
             *******************************************/
            $memberEmailData['template'] = "emails.manager.clientNotAvailable";
            $memberEmailData['subject'] = 'マイチューター：レッスン欠席のご案内'; //My Tutor: Lesson Absence
            $memberEmailData['email'] = $memberInfo->user->email; //recipient (mailto:)

            self::dispatchEmail($memberEmailData, $memberInfo, $tutorInfo, $selectedSchedule);

            /*******************************************
             *       SEND MAIL TO TUTOR
             *******************************************/
            $tutorEmaildata['template'] = "emails.tutor.tutorNotifyCancelled";
            $tutorEmaildata['subject'] = 'My Tutor: Lesson Schedule Cancelled'; //Cancelled
            $tutorEmaildata['email'] = $tutorInfo->user->email; //recipient (mailto:)

            self::dispatchEmail($tutorEmaildata, $memberInfo, $tutorInfo, $selectedSchedule); //Add to Queue

        } else if ($selectedSchedule->schedule_status == 'COMPLETED') {

            /*******************************************
             * NO EMAIL, JUST SILENT MARKED COMPLETED
             *******************************************/

        }

    }

    public function dispatchEmail($emailData, $memberInfo, $tutorInfo, $selectedSchedule)
    {
        $job = new \App\Jobs\SendEmailJob($emailData, $memberInfo, $tutorInfo, $selectedSchedule);
        dispatch($job); //Add to Queue
    }

}
