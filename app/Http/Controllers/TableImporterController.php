<?php

namespace App\Http\Controllers;

use App\Models\AgentTransaction;
use DB;

class TableImporterController extends Controller
{
    public function test($id = null)
    {
        $start = ($id - 1) * 100000;
        $end = $id * 100000;

        echo $start;
        echo "<BR>";
        echo $end;
    }

    public function importAgentTranscationsIndex() 
    {        
        $items = DB::connection('mysql_live')->table('agent_transaction')->count();
        $per_item = 8000;
        $total_pages = ($items / $per_item) + 1;

        for($i=1; $i <= $total_pages; $i++)
         {
            $url = url("importAgentTranscations/$i");

            echo "<a href='$url'><small>Transaction Import Page $i</small></a><br>";
        }
    }

    
    public function update($memberID) 
    {

        $items = DB::connection('mysql_live')->select("select * from agent_transaction where member_id = $memberID");

        $ctr = 0;

        AgentTransaction::where('member_id', $memberID)->delete();

        foreach ($items as $item) {

            $ctr = $ctr + 1;

            if (AgentTransaction::where('id', $item->id)->exists()) 
            {

                $data = [
                    //'id' => $item->id,
                    'created_at' => $item->created_on,
                    'updated_at' => $item->updated_on,
                    'valid' => $item->valid,
                    'amount' => $item->amount,
                    'remarks' => $item->remarks,
                    'transaction_type' => $item->transaction_type,
                    'agent_id' => $item->agent_id,
                    'created_by_id' => $item->created_by_id,
                    'member_id' => $item->member_id,
                    'schedule_item_id' => $item->schedule_item_id,
                    'price' => $item->price,
                    'lesson_shift_id' => $item->lesson_shift_id,
                    'credits_expiration' => $item->credits_expiration,
                    'old_credits_expiration' => $item->old_credits_expiration,
                ];


                echo "<div style='color:blue'>$ctr - Added : " . $item->id . " " . $item->created_on . "</div>";


                

                $member = AgentTransaction::where('member_id', $memberID)->first();
                $transaction = $member->create($data);  

                $member = AgentTransaction::where('member_id', $memberID)->first();

                echo "<pre>";
                print_r ($member);
                echo "</pre>";
            }

            
        }

        echo "done! updating";

    }



    public function importAgentTranscations($id = null, $per_item = null)
    {
        set_time_limit(0);

        if ($per_item == null) {
            $per_item = 8000;
        }

        $start = ($id - 1) * ($per_item);
        $end = $id * ($per_item);



        echo "<div>ADDING agent_transcations FROM : " . $start . " - " . $end . "</div>";
        echo "<BR>";

        //The SQL query below says "return only 10 records, start on record 16 (OFFSET 15)":
        //$sql = "SELECT * FROM Orders LIMIT 10 OFFSET 15";        

        $items = DB::connection('mysql_live')->select("select * from agent_transaction LIMIT $per_item OFFSET $start");

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
                'amount' => $item->amount,
                'remarks' => $item->remarks,
                'transaction_type' => $item->transaction_type,
                'agent_id' => $item->agent_id,
                'created_by_id' => $item->created_by_id,
                'member_id' => $item->member_id,
                'schedule_item_id' => $item->schedule_item_id,
                'price' => $item->price,
                'lesson_shift_id' => $item->lesson_shift_id,
                'credits_expiration' => $item->credits_expiration,
                'old_credits_expiration' => $item->old_credits_expiration,
            ];

            if (AgentTransaction::where('id', $item->id)->exists()) {
                echo "<div style='color:red'>$ctr - EXISTING : " . $item->id . " " . $item->created_on . "</div>";
            } else {

                try
                {
                    $transaction = AgentTransaction::updateOrCreate($data);

                    DB::commit();

                    echo "<div style='color:blue'>$ctr - Added : " . $item->id . " " . $item->created_on . "</div>";

                } catch (\Exception $e) {

                    echo "<div style='color:red'>$ctr - Exception Error Found : " . $e->getMessage() ." on Line : " . $e->getLine() ."</div>";
                }

            }
        }

        echo "success!!! data imported";

    }
}
