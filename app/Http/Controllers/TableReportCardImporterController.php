<?php

namespace App\Http\Controllers;

use App\Models\ReportCard;
use DB;

class TableReportCardImporterController extends Controller
{
    public function index($per_item = null)
    {
        $items = DB::connection('mysql_live')->table('report_card')->count();

        $items_local = DB::connection('mysql')->table('report_card')->count();


        echo "<div>there are ". $items ." reports on live </div>";
        echo "<div>there are ". $items_local ." reports on local </div>";

        if ($per_item == null) {
            $per_item = 8000;
        }
       
        $total_pages = ($items / $per_item) + 1;

        $counter = 0;

        for ($i = 1; $i <= $total_pages; $i++) {

            $start = ($i - 1) * ($per_item);
            $end = $i * ($per_item);
            $items_live_count = DB::connection('mysql_live')->table('report_card')->select('id')->orderBy('id', 'ASC')->take($per_item)->skip($start)->get();
            $items_local_count = DB::connection('mysql')->table('report_card')->select('id')->orderBy('id', 'ASC')->take($per_item)->skip($start)->get();

            $counter =  $counter + count( $items_local_count->toArray() );

            if ($items_local_count < $items_live_count) 
            {
                echo "<p>===========================$i================================================</p>";

                echo  "<p> Live Count: ". count( $items_live_count->toArray() ) . " | Local Count "  . count( $items_local_count->toArray() ) ."</p>";

                $live_count = count( $items_live_count->toArray() );
                $local_count = count( $items_local_count->toArray() );

                $total_missing = $live_count - $local_count;

                /*
                $items_live = DB::connection('mysql_live')->table('report_card')->select('id')->orderBy('id', 'ASC')->take($per_item)->skip($start)->pluck('id');
                $items_local = DB::connection('mysql')->table('report_card')->select('id')->orderBy('id', 'ASC')->take($per_item)->skip($start)->get()->pluck('id');
    
                
                $live = $items_live->toArray();
                $local = $items_local->toArray();
    
                $result = array_diff($live, $local);
    
                $diff = implode(", ", $result);   
                */

                $url = url("importReportCards/$i/$per_item");
                echo "<div><a href='$url' style='color:red'><small>User Page $i</small></a>  Start : $start - $end  Missing : $total_missing </div>";
                

            } else {
                echo "<p>===========================$i>================================================</p>";

                $url = url("importReportCards/$i/$per_item");
                echo "<div><a href='$url'><small>User Page $i</small></a>  Start : $start - $end  | Live Count : " .  count( $items_live_count->toArray() ) . " | Local Count "  .count( $items_local_count->toArray() ) ." </div>";                

            }
            
        }

        echo $counter ." local items | live items : ". $items;
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

        echo "<div>ADDING report cards  FROM : " . ($start + 1) . " - " . $end . "</div>";
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