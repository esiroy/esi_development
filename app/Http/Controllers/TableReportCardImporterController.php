<?php

namespace App\Http\Controllers;

use App\Models\ReportCard;
use DB;

class TableReportCardImporterController extends Controller
{
    public function index($per_item = null)
    {
        $items = DB::connection('mysql_live')->table('report_card')->count();

        if ($per_item == null) {
            $per_item = 8000;
        }
       
        $total_pages = ($items / $per_item) + 1;

        for ($i = 1; $i <= $total_pages; $i++) {
            
            $url = url("importReportCards/$i/$per_item");

            echo "<a href='$url'><small>User Page $i</small></a><br>";
        }
    }

    public function compare()
    {
        $items = DB::connection('mysql_live')->table('report_card')->get();

        foreach ($items as $item) {
            $itemLiveArray[$item->id] = $item->id;
        }

        $localItems = ReportCard::select('id')->get();      
        foreach ($localItems as $item) {
            $itemLocalArray[$item->id] = $item->id;
        }

        $itemDifferences = array_diff($itemLiveArray, $itemLocalArray);

        foreach($itemDifferences as $diff) {

            if (ReportCard::where('id', $diff)->exists()) {
                echo "<div>this does not exists in our table";
            }
            //$items = DB::connection('mysql_live')->select("select * from report_card where id = $diff");            
        }
    }

    public function show($id = null, $per_item = null)
    {
        set_time_limit(0);

        if ($per_item == null) {
            $per_item = 8000;
        }

        $start = ($id - 1) * ($per_item);
        $end = $id * ($per_item);

        echo "<div>ADDING report cards  FROM : " . $start . " - " . $end . "</div>";
        echo "<BR>";

        
        $items = DB::connection('mysql_live')->select("select * from report_card ORDER BY id ASC LIMIT $per_item OFFSET $start");

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
                'comment' => $item->comment,
                'grade' => $item->grade,
                'schedule_item_id' => $item->schedule_item_id,
                'course_category_id' => $item->course_category_id,
                'course_item_id' => $item->course_item_id,
                'lesson_course' => $item->lesson_course,
                'lesson_material' => $item->lesson_material,
                'lesson_subject' => $item->lesson_subject,
                'member_id' => $item->member_id,
                'lesson_level' => $item->lesson_level                              
            ];

            if (ReportCard::where('id', $item->id)->exists()) {

                try
                {

                    $reportCardObj = ReportCard::where('id', $item->id)->first();

                    $reportCard = $reportCardObj->update($data);

                    DB::commit();

                    echo "<div style='color:green'>$ctr - updated : " . $item->id . "  </div>";

                } catch (\Exception $e) {

                    echo "<div style='color:red'>$ctr - Exception Error Found : " . $e->getMessage() . " on Line : " . $e->getLine() . " On update </div>";
                }

            } else {

                try
                {
                    $reportCardObj = ReportCard::insert($data);

                    DB::commit();

                    echo "<div style='color:blue'>$ctr - added : " . $item->id . " - " . $item->created_on . "</div>";

                } catch (\Exception $e) {

                    echo "<div style='color:red'>$ctr - $item->id Exception Error Found : " . $e->getMessage() . " on Line : " . $e->getLine() . " On Insert</div>";
                }

            }
        }

        echo "success!!! data imported";

    }

    public function edit($id) {

        $items = DB::connection('mysql_live')->select("select * from report_card where user_id = $id");

        foreach ($items as $item) {

            $data = [
                'id' => $item->id,
                'created_at' => $item->created_on,
                'updated_at' => $item->updated_on,
                'valid' => $item->valid,
                'comment' => $item->comment,
                'grade' => $item->grade,
                'schedule_item_id' => $item->schedule_item_id,
                'course_category_id' => $item->course_category_id,
                'course_item_id' => $item->course_item_id,
                'lesson_course' => $item->lesson_course,
                'lesson_material' => $item->lesson_material,
                'lesson_subject' => $item->lesson_subject,
                'member_id' => $item->member_id,
                'lesson_level' => $item->lesson_level                              
            ];
            
            if (ReportCard::where('id', $item->id)->exists()) {

                echo "<div style='color:red'> EXISTING : " . $item->id . "</div>";

                try
                {
                    $reportCardObj = ReportCard::where('id', $item->id)->first();

                    $reportCard = $reportCardObj->update($data);

                    DB::commit();

                    echo "<div style='color:green'> - updated : " . $item->id . "</div>";

                } catch (\Exception $e) {

                    echo "<div style='color:red'>". $item->id  ." - Exception Error Found : ( Member Update ) " . $e->getMessage() . " on Line : " . $e->getLine() . " On update </div>";
                }

            } else {

                try
                {
                    $reportCard = ReportCard::create($data);
                    
                    DB::commit();

                    echo "<div style='color:blue'> - added : " . $item->id . "</div>";

                } catch (\Exception $e) {

                    
                    echo "<div style='color:red'>" . $item->id  ." - Exception Error Found : (Member Insert) " . $e->getMessage() . " on Line : " . $e->getLine() . " On Insert <BR></div> <br>";
                }

            }


        }
    }

}
