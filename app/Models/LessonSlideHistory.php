<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class LessonSlideHistory extends Model
{
    public $table = 'lesson_slide_history';

    public $timestamps = true;

    protected $guarded = array('created_at', 'updated_at');

  
    public function saveSlideHistory($slideIndex, $totalSlides, $reservation, $canvasData, $imageData) 
    {
        $scheduleID =  $reservation['schedule_id'];

        $lessonHistory = LessonHistory::where('schedule_id',  $scheduleID )->where('status', "NEW")->first();

        if ($lessonHistory) {


            $lessonHistory->update([
                'current_slide'   => $slideIndex,
                'total_slides'      => $totalSlides
            ]);



            $lessonSlideHistory = LessonSlideHistory::where('slide_index', $slideIndex)->where('lesson_history_id',  $lessonHistory->id )->first();

            if ($lessonSlideHistory) {
            
                $updated =  $lessonSlideHistory->update([            
                    'content' => $canvasData,  
                    'data'    => $imageData
                ]);

                if ($updated) {
                
                    $response = [
                            'success'           => true,                
                            'message'           => "Lesson history updated successfully",
                            'lesson_history'    => $lessonHistory,     
                            "created"           => $updated
                        ];

                } else {
                
                    $response = [
                        'success' => false,
                        'message' => "Lesson history encountered an error"
                    ];        
                }
              
            
            } else {
            
                
                   // @note: the lesson slide history is not created yet, we need to create it!
                

                $created = LessonSlideHistory::create([
                    'slide_index'       => $slideIndex,
                    'lesson_history_id' => $lessonHistory->id,            
                    'content'           => $canvasData,
                    'data'              => $imageData
                ]);

                if ($created) {
                
                    $response = [
                            'success'    => true,                
                            'message'    => "Lesson history created successfully",
                            "created"    => $created
                        ];

                } else {
                
                    $response = [
                        'success' => false,
                        'message' => "Lesson history encountered an error"
                    ];        
                }



            }


            return  (object) $response;

        }

     

    }

    public function getSlideHistory($slideIndex, $scheduleID) {


        $lessonHistory = LessonHistory::where('schedule_id',  $scheduleID )->where('status', "NEW")->first();

        if ($lessonHistory) 
        {
            $lessonSlideHistory = LessonSlideHistory::where('slide_index', $slideIndex)->where('lesson_history_id',  $lessonHistory->id )->first();

            if ($lessonSlideHistory)  {
            
                    $response = [
                            'success'   => true,                
                            'message'   => "Lesson slide history found successfully",
                            'data'      => $lessonSlideHistory
                        ];

            } else {
            
                    $response = [
                            'success'   => false,                
                            'message'   => "Lesson slide history was not found"
                        ];  
            }
        } else {
        

            $response = [
                    'success'   => false,                
                    'message'   => "Lesson history was not found"
                ];  

        }
    
    }

    public function getAllSlideHistory($scheduleID) {
    
        //$lessonHistory = LessonHistory::where('schedule_id',  $scheduleID )->where('status', "NEW")->first();

        $lessonHistory = LessonHistory::where('schedule_id',  $scheduleID )->where(function ($query) 
        {
            $query->where('status', '=', "NEW")
                    ->orWhere('status', '=', "INCOMPLETE")
                    ->orWhere('status', '=', "COMPLETED");

        })->orderBy('id', 'DESC')->first();

        if ($lessonHistory) 
        {
            $lessonSlideHistory = LessonSlideHistory::where('lesson_history_id',  $lessonHistory->id )->orderBy('slide_index', 'ASC')->get();

            if ($lessonSlideHistory)  {
            
                $response = (object) [
                        'success'   => true,                
                        'message'   => "Lesson slide history found successfully",
                        'data'      => $lessonSlideHistory
                    ];

            } else {

                $response = (object) [
                        'success'   => false,                
                        'message'   => "Lesson slide history was not found"
                    ];         
            
            }

        } else {
        
            $response = (object) [
                    'success'   => false,                
                    'message'   => "Lesson history was not found"
                ];          
        }


        return $response;

    }
}
