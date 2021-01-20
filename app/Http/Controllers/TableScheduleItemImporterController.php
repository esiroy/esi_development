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

        $itemLiveArray = null;
        $itemLocalArray = null;

        $items = DB::connection('mysql_live')->table('schedule_item')->select('id')->orderBy('id', 'desc')->limit(20000)->get();
        foreach ($items as $item) {
            $itemLiveArray[$item->id] = $item->id;
        }

        $localItems = ScheduleItem::select('id')->orderBy('id', 'desc')->limit(20000)->get();
        foreach ($localItems as $item) {
            $itemLocalArray[$item->id] = $item->id;
        }

        $itemDifferences = array_diff($itemLiveArray, $itemLocalArray);

        $differenceCount = count($itemDifferences);

        echo $differenceCount . " are missing in your schedule items table<BR>";


        foreach ($itemDifferences as $item) {
            $itemID = $item;

            $liveItems = DB::connection('mysql_live')->select("select * from schedule_item where id = $itemID");

            $ctr = 0;

            foreach ($liveItems as $liveItem) {
                $ctr = $ctr + 1;

                $data = [
                    'id' => $liveItem->id,
                    'created_at' => $liveItem->created_on,
                    'updated_at' => $liveItem->updated_on,
                    'valid' => $liveItem->valid,
                    'lesson_time' => $liveItem->lesson_time,
                    'tutor_id' => $liveItem->tutor_id,
                    'member_id' => $liveItem->member_id,
                    'schedule_status' => $liveItem->schedule_status,
                    'duration' => $liveItem->duration,
                    'lesson_shift_id' => $liveItem->lesson_shift_id,
                    'memo' => $liveItem->memo,                    
                    //'email_type' => $liveItem->email_type,                 
                ];

                $transaction = ScheduleItem::insert($data);
                echo "<div style='color:blue'>$ctr - Added : " . $liveItem->id . " " . $liveItem->created_on . "</div>";

            }

        }

    }

    public function importSchedulesIndex()
    {
        $items = DB::connection('mysql_live')->table('schedule_item')->count();
        $per_item = 8000;
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
            $per_item = 8000;
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
