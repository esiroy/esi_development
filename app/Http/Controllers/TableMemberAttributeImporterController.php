<?php

namespace App\Http\Controllers;

use App\Models\MemberAttribute;
use DB;

class TableMemberAttributeImporterController extends Controller
{

    public function index()
    {
        set_time_limit(0);

        $items = DB::connection('mysql_live')->table('member_attribute')->count();

        echo "<div>there are " . $items . " member attribute item</div>";

        $per_item = 8000;
        $total_pages = ($items / $per_item) + 1;

        $counter = 0;

        for ($i = 1; $i <= $total_pages; $i++) {

            set_time_limit(0);

            $start = ($i - 1) * ($per_item);
            $end = $i * ($per_item);
            $items_live_count = DB::connection('mysql_live')->table('member_attribute')->select('id')->orderBy('id', 'ASC')->take($per_item)->skip($start)->get();
            $items_local_count = DB::connection('mysql')->table('member_attribute')->select('id')->orderBy('id', 'ASC')->take($per_item)->skip($start)->get();

            $counter = $counter + count($items_local_count->toArray());

            if ($items_local_count < $items_live_count) {

                $live_count = count($items_live_count->toArray());
                $local_count = count($items_local_count->toArray());

                $total_missing = $live_count - $local_count;

                $url = url("importMemberAttribute/$i");
                echo "<a href='$url'><small>Member Attribute Page $i</small>   <span style='color:red'>Total Missing: $total_missing </span> </a><br>";

            } else {

                $url = url("importMemberAttribute/$i");
                echo "<a href='$url'><small>Member Attribute Page $i</small> </a><br>";
            }
        }

        echo $counter . " local items | live items : " . $items;

    }

    public function update($id)
    {
        $items = DB::connection('mysql_live')->select("select * from member_attribute where id = $id");
        $ctr = 0;

        foreach ($items as $item) {

            $ctr = $ctr + 1;

            $data = [
                'id' => $item->id,
                'created_at' => $item->created_on,
                'updated_at' => $item->updated_on,
                'valid' => $item->valid,
                'attribute' => $item->attribute,
                'lesson_limit' => $item->lesson_limit,
                'month' => $item->month,
                'year' => $item->year,
                'member_id' => $item->member_id                
            ];

            if (MemberAttribute::where('id', $item->id)->exists()) {
                $memberAttribute = MemberAttribute::where('id', $id)->first();
                $transaction = $memberAttribute->update($data);

                echo "<div style='color:yellow'>$ctr - Added : " . $item->id . " " . $item->created_on . "</div>";
            } else {
                $transaction = MemberAttribute::insert($data);
                echo "<div style='color:blue'>$ctr - Added : " . $item->id . " " . $item->created_on . "</div>";
            }

        }

        echo "done! updating";

    }

    public function show($id = null, $per_item = null)
    {


        echo $id;

        set_time_limit(0);

        if ($per_item == null) {
            $per_item = 8000;
        }

        $start = ($id - 1) * ($per_item);
        $end = $id * ($per_item);

        echo "<div>ADDING member_attribute FROM : " . $start . " - " . $end . "</div>";
        echo "<BR>";


        $items = DB::connection('mysql_live')->select("select * from member_attribute ORDER BY id ASC LIMIT $per_item OFFSET $start");

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
                'attribute' => $item->attribute,
                'lesson_limit' => $item->lesson_limit,
                'month' => $item->month,
                'year' => $item->year,
                'member_id' => $item->member_id                
            ];

            if (MemberAttribute::where('id', $item->id)->exists()) {
                echo "<div style='color:red'>$ctr - EXISTING : " . $item->id . " " . $item->created_on . "</div>";

                try
                {
                    $memberAttribute = MemberAttribute::where('id', $item->id);
                    $transaction = $memberAttribute->update($data);
                    DB::commit();
                    echo "<div style='color:blue'>$ctr - updated : " . $item->id . " " . $item->created_on . "</div>";
                } catch (\Exception $e) {
                    echo "<div style='color:red'>$ctr - Exception Error Found : " . $e->getMessage() . " on Line : " . $e->getLine() . " On update </div>";
                }

            } else {

                try
                {
                    $transaction = MemberAttribute::insert($data);
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
