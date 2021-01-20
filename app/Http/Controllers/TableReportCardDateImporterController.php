<?php

namespace App\Http\Controllers;

use App\Models\ReportCardDate;
use DB;

class TableReportCardDateImporterController extends Controller
{
    public function index($per_item = null)
    {
        $items = DB::connection('mysql_live')->table('report_card_date')->count();

        if ($per_item == null) {
            $per_item = 8000;
        }
       
        $total_pages = ($items / $per_item) + 1;

        for ($i = 1; $i <= $total_pages; $i++) {
            
            $url = url("importReportCardsDate/$i/$per_item");

            echo "<a href='$url'><small>User Page $i</small></a><br>";
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

        echo "<div>ADDING report cards Date FROM : " . $start . " - " . $end . "</div>";
        echo "<BR>";

        
        $items = DB::connection('mysql_live')->select("select * from report_card_date ORDER BY id ASC LIMIT $per_item OFFSET $start");

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
                'file_name' => $item->file_name,
                'file_path' => $item->file_path,
                'grade' => $item->grade,
                'lesson_course' => $item->lesson_course,
                'lesson_date' => $item->lesson_date,
                'lesson_material' => $item->lesson_material,
                'lesson_subject' => $item->lesson_subject,
                'created_by_id' => $item->created_by_id,
                'member_id' => $item->member_id,
                'tutor_id' => $item->tutor_id,
                'display_tutor_name' => $item->display_tutor_name,
            ];


            if (ReportCardDate::where('id', $item->id)->exists()) {
                echo "<div style='color:red'>$ctr - EXISTING : " . $item->id . " - " . $item->created_on . "</div>";

                try
                {

                    $reportCardObj = ReportCardDate::where('id', $item->id)->first();

                    $reportCard = $reportCardObj->update($data);

                    DB::commit();

                    echo "<div style='color:green'>$ctr - updated : " . $item->id . $item->created_on . "</div>";

                } catch (\Exception $e) {

                    echo "<div style='color:red'>$ctr - Exception Error Found : " . $e->getMessage() . " on Line : " . $e->getLine() . " On update </div>";
                }

            } else {

                try
                {
                    $reportCardObj = ReportCardDate::insert($data);

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

        $items = DB::connection('mysql_live')->select("select * from report_card_date where user_id = $id");

        foreach ($items as $item) {

            $data = [
                'id' => $item->id,
                'created_at' => $item->created_on,
                'updated_at' => $item->updated_on,
                'valid' => $item->valid,
                'comment' => $item->comment,
                'file_name' => $item->file_name,
                'file_path' => $item->file_path,
                'grade' => $item->grade,
                'lesson_course' => $item->lesson_course,
                'lesson_date' => $item->lesson_date,
                'lesson_material' => $item->lesson_material,
                'lesson_subject' => $item->lesson_subject,
                'created_by_id' => $item->created_by_id,
                'member_id' => $item->member_id,
                'tutor_id' => $item->tutor_id,
                'display_tutor_name' => $item->display_tutor_name,
            ];
            
            if (ReportCardDate::where('id', $item->id)->exists()) {

                echo "<div style='color:red'> EXISTING : " . $item->id . "</div>";

                try
                {
                    $reportCardObj = ReportCardDate::where('id', $item->id)->first();

                    $reportCard = $reportCardObj->update($data);

                    DB::commit();

                    echo "<div style='color:green'> - updated : " . $item->id . "</div>";

                } catch (\Exception $e) {

                    echo "<div style='color:red'>". $item->id  ." - Exception Error Found : ( Member Update ) " . $e->getMessage() . " on Line : " . $e->getLine() . " On update </div>";
                }

            } else {

                try
                {
                    $reportCard = ReportCardDate::create($data);
                    
                    DB::commit();

                    echo "<div style='color:blue'> - added : " . $item->id . "</div>";

                } catch (\Exception $e) {

                    
                    echo "<div style='color:red'>" . $item->id  ." - Exception Error Found : (Member Insert) " . $e->getMessage() . " on Line : " . $e->getLine() . " On Insert <BR></div> <br>";
                }

            }


        }
    }

}
