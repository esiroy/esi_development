<?php

namespace App\Http\Controllers;

use App\Models\ScheduleItem;
use DB;

class TableScheduleItemImporterController extends Controller
{
    public function test($id = null)
    {
        $start = ($id - 1) * 100000;
        $end = $id * 100000;

        echo $start;
        echo "<BR>";
        echo $end;
    }

    public function compare()
    {
        $items = DB::connection('mysql_live')->table('schedule_item')->count();

        echo "Live Item count : $items";

        echo "<br>";

        echo "Our Current Item Count : " . ScheduleItem::count();
    }

    public function getNewTransactions()
    {
        set_time_limit(0);


        //The SQL query below says "return only 10 records, start on record 16 (OFFSET 15)":
        //$sql = "SELECT * FROM Orders LIMIT 10 OFFSET 15";

        $items = DB::connection('mysql_live')->select("select * from schedule_item ORDER BY id DESC LIMIT 10000");

        DB::beginTransaction();

        $ctr = 0;

        foreach ($items as $item) {

            set_time_limit(0);

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

            if (ScheduleItem::where('id', $item->id)->exists()) {
                echo "<div style='color:red'>$ctr - EXISTING : " . $item->id . " " . $item->created_on . "</div>";

                try
                {

                    $scheduleObj = ScheduleItem::where('id', $item->id);

                    $transaction = $scheduleObj->update($data);

                    DB::commit();

                    echo "<div style='color:blue'>$ctr - updated : " . $item->id . " " . $item->created_on . "</div>";

                } catch (\Exception $e) {

                    echo "<div style='color:red'>$ctr - Exception Error Found : " . $e->getMessage() . " on Line : " . $e->getLine() . " On update </div>";
                }

            } else {

                try
                {
                    $transaction = ScheduleItem::insert($data);

                    DB::commit();

                    echo "<div style='color:blue'>$ctr - Added : " . $item->id . " " . $item->created_on . "</div>";

                } catch (\Exception $e) {

                    echo "<div style='color:red'>$ctr - Exception Error Found : " . $e->getMessage() . " on Line : " . $e->getLine() . " On Insert</div>";
                }

            }
        }

        echo "success!!! data imported";

    }

    public function index($per_item = null)
    {
        $items = DB::connection('mysql_live')->table('schedule_item')->count();

        if ($per_item == null) {
            $per_item = 5000;
        }

        $total_pages = ($items / $per_item) + 1;

        for ($i = 1; $i <= $total_pages; $i++) {
            $url = url("importSchedules/import/$i");

            echo "<a href='$url'><small>Transaction Import Page $i</small></a><br>";
        }
    }

    public function update($id)
    {

        $items = DB::connection('mysql_live')->select("select * from schedule_item where id = $id");

        $ctr = 0;

        //ScheduleItem::where('id', $id)->delete();

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
                
                echo "<div style='color:yellow'>$ctr - Added : " . $item->id . " " . $item->created_on . "</div>";
            } else {
                $transaction = ScheduleItem::insert($data);
                echo "<div style='color:blue'>$ctr - Added : " . $item->id . " " . $item->created_on . "</div>";
            }

        }

        echo "done! updating";

    }

    public function importSchedules($id = null, $per_item = null)
    {
        set_time_limit(0);

        if ($per_item == null) {
            $per_item = 5000;
        }

        $start = ($id - 1) * ($per_item);
        $end = $id * ($per_item);

        echo "<div>ADDING schedule_item FROM : " . $start . " - " . $end . "</div>";
        echo "<BR>";

        //The SQL query below says "return only 10 records, start on record 16 (OFFSET 15)":
        //$sql = "SELECT * FROM Orders LIMIT 10 OFFSET 15";

        $items = DB::connection('mysql_live')->select("select * from schedule_item ORDER BY id ASC LIMIT $per_item OFFSET $start");

        DB::beginTransaction();

        $ctr = 0;

        foreach ($items as $item) {

            set_time_limit(0);

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

            if (ScheduleItem::where('id', $item->id)->exists()) {
                echo "<div style='color:red'>$ctr - EXISTING : " . $item->id . " " . $item->created_on . "</div>";

                try
                {

                    $scheduleObj = ScheduleItem::where('id', $item->id);

                    $transaction = $scheduleObj->update($data);

                    DB::commit();

                    echo "<div style='color:blue'>$ctr - updated : " . $item->id . " " . $item->created_on . "</div>";

                } catch (\Exception $e) {

                    echo "<div style='color:red'>$ctr - Exception Error Found : " . $e->getMessage() . " on Line : " . $e->getLine() . " On update </div>";
                }

            } else {

                try
                {
                    $transaction = ScheduleItem::insert($data);

                    DB::commit();

                    echo "<div style='color:blue'>$ctr - Added : " . $item->id . " " . $item->created_on . "</div>";

                } catch (\Exception $e) {

                    echo "<div style='color:red'>$ctr - Exception Error Found : " . $e->getMessage() . " on Line : " . $e->getLine() . " On Insert</div>";
                }

            }
        }

        echo "success!!! data imported";

    }
}
