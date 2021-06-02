<?php

namespace App\Http\Controllers;

use App\Models\ScheduleItem;
use DB;

class TableSchedulerUpdaterController extends Controller
{  

    public function index($date)
    {
        set_time_limit(0);

        $dateFrom = date('Y-m-d', strtotime($date));
                
                
        $items = DB::connection('mysql_live')->table('schedule_item')
        ->whereDate('lesson_time', $dateFrom )
        ->get();

        $ctr = 0;

        foreach ($items as $item) {
            $url = url("scheduleUpdater/$item->id");
            //echo "<a href='$url'> $item->id - $item->lesson_time </a><br>";

            $data = [
                'id' => $item->id,
                'created_at' => $item->created_on,
                'updated_at' => $item->updated_on,
                'valid' => $item->valid,
                'lesson_time' => $item->lesson_time,
                'tutor_id' => $item->tutor_id,
                'member_id' => $item->member_id,
                'schedule_status' => $item->schedule_status,
                'duration' => $item->duration,
                'lesson_shift_id' => $item->lesson_shift_id,
                'memo' => $item->memo,                    
                //'email_type' => $liveItem->email_type,                 
            ];
            
    
            if (ScheduleItem::where('id', $item->id)->exists()) 
            {
                $ctr = $ctr + 1;

                $scheduleItem = ScheduleItem::where('id', $item->id)->first();
                $transaction = $scheduleItem->update($data);
                
                echo "<pre>";
                print_r ($data);
                echo "</pre>";
                
                echo "<div style='color:green'>$ctr - updated : " . $item->id . " " . $item->lesson_time . "</div>";
            } else {

                $ctr = $ctr + 1;

                $transaction = ScheduleItem::insert($data);
                echo "<div style='color:blue'>$ctr - Added : " . $item->id . " " . $item->lesson_time . "</div>";
            }            


        }

    }

    public function show($id)
    {

        $items = DB::connection('mysql_live')->select("select * from schedule_item where id = $id");

        $ctr = 0;

        if ($items) {        


            foreach ($items as $item) {

                $ctr = $ctr + 1;
    
                $data = [
                    'id' => $item->id,
                    'created_at' => $item->created_on,
                    'updated_at' => $item->updated_on,
                    'valid' => $item->valid,
                    'lesson_time' => $item->lesson_time,
                    'tutor_id' => $item->tutor_id,
                    'member_id' => $item->member_id,
                    'schedule_status' => $item->schedule_status,
                    'duration' => $item->duration,
                    'lesson_shift_id' => $item->lesson_shift_id,
                    'memo' => $item->memo,                    
                    //'email_type' => $liveItem->email_type,                 
                ];
    
                if (ScheduleItem::where('id', $item->id)->exists()) 
                {
                    $scheduleItem = ScheduleItem::where('id', $id)->first();
                    $transaction = $scheduleItem->update($data);
                    
                    echo "<pre>";
                    print_r ($data);
                    echo "</pre>";
                    
                    echo "<div style='color:green'>$ctr - updated : " . $item->id . " " . $item->created_on . "</div>";
                } else {
                    $transaction = ScheduleItem::insert($data);
                    echo "<div style='color:blue'>$ctr - Added : " . $item->id . " " . $item->created_on . "</div>";
                }
    
            }
    
            echo "done! updating";

            exit();

        } else {

            echo "no items";
            exit();
        }



    }

}
