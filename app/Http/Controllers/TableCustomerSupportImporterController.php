<?php

namespace App\Http\Controllers;

use App\Models\CustomerSupport;
use DB;

class TableCustomerSupportImporterController extends Controller
{

    public function index()
    {
        set_time_limit(0);

        $items = DB::connection('mysql_live')->table('customersupport')->count();

        echo "<div>there are " . $items . " questioinnaire item</div>";

        $per_item = 8000;
        $total_pages = ($items / $per_item) + 1;

        $counter = 0;

        for ($i = 1; $i <= $total_pages; $i++) {

            set_time_limit(0);

            $start = ($i - 1) * ($per_item);
            $end = $i * ($per_item);
            $items_live_count = DB::connection('mysql_live')->table('customersupport')->select('id')->orderBy('id', 'ASC')->take($per_item)->skip($start)->get();
            $items_local_count = DB::connection('mysql')->table('customer_support')->select('id')->orderBy('id', 'ASC')->take($per_item)->skip($start)->get();

            $counter = $counter + count($items_local_count->toArray());

            if ($items_local_count < $items_live_count) {

                $live_count = count($items_live_count->toArray());
                $local_count = count($items_local_count->toArray());

                $total_missing = $live_count - $local_count;

                $url = url("importCustomerSupport/$i/$per_item");
                echo "<a href='$url'><small>Customer Support Page $i</small>   <span style='color:red'>Total Missing: $total_missing </span> </a><br>";

            } else {

                $url = url("importCustomerSupport/$i/$per_item");
                echo "<a href='$url'><small>Customer Support Page $i</small> </a><br>";
            }
        }

        echo $counter . " local items | live items : " . $items;

    }

    public function update($id)
    {
        $items = DB::connection('mysql_live')->select("select * from customersupport where id = $id");
        $ctr = 0;

        foreach ($items as $item) {

            $ctr = $ctr + 1;

            $data = [
                'id' => $item->id,
                'created_at' => $item->created_on,
                'updated_at' => $item->updated_on,
                'valid' => $item->valid,
                'inquiry' => $item->inquiry,
                'user_id' => $item->user_id,
                'member_id' => $item->member_id
            ];

            if (CustomerSupport::where('id', $item->id)->exists()) {
                $scheduleItem = CustomerSupport::where('id', $id)->first();
                $transaction = $scheduleItem->update($data);

                echo "<div style='color:yellow'>$ctr - Added : " . $item->id . " " . $item->created_on . "</div>";
            } else {
                $transaction = CustomerSupport::insert($data);
                echo "<div style='color:blue'>$ctr - Added : " . $item->id . " " . $item->created_on . "</div>";
            }

        }

        echo "done! updating";

    }

    public function show($id = null, $per_item = null)
    {


        set_time_limit(0);

        if ($per_item == null) {
            $per_item = 8000;
        }

        $start = ($id - 1) * ($per_item);
        $end = $id * ($per_item);

        echo "<div>ADDING customer_support FROM : " . $start . " - " . $end . "</div>";
        echo "<BR>";

        //The SQL query below says "return only 10 records, start on record 16 (OFFSET 15)":
        //$sql = "SELECT * FROM Orders LIMIT 10 OFFSET 15";

        $items = DB::connection('mysql_live')->select("select * from customersupport ORDER BY id ASC LIMIT $per_item OFFSET $start");

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
                'inquiry' => $item->inquiry,
                'user_id' => $item->user_id,
                'member_id' => $item->member_id,                
            ];

            if (CustomerSupport::where('id', $item->id)->exists()) {
                echo "<div style='color:red'>$ctr - EXISTING : " . $item->id . " " . $item->created_on . "</div>";

                try
                {
                    $scheduleObj = CustomerSupport::where('id', $item->id);
                    $transaction = $scheduleObj->update($data);
                    DB::commit();
                    echo "<div style='color:blue'>$ctr - updated : " . $item->id . " " . $item->created_on . "</div>";
                } catch (\Exception $e) {
                    echo "<div style='color:red'>$ctr - Exception Error Found : " . $e->getMessage() . " on Line : " . $e->getLine() . " On update </div>";
                }

            } else {

                try
                {
                    $transaction = CustomerSupport::insert($data);
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