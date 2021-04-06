<?php

namespace App\Http\Controllers;

use App\Models\AgentTransaction;
use DB;

class TableGetAgentTransactionController extends Controller
{

    public function index($per_item = null)
    {
        set_time_limit(0);

        $items = DB::connection('mysql_live')->table('agent_transaction')->count();

        $live_item_count = count($items->toArray());

        if ($per_item == null) {
            $per_item = 8000;
        }
       
        $total_pages = ($items / $per_item) + 1;

        $counter = 0;

        for ($i = 1; $i <= $total_pages; $i++) 
        {       

            $start = ($i - 1) * ($per_item);
            $end = $i * ($per_item);

            $items_live_count = DB::connection('mysql_live')->table('agent_transaction')->select('id')->orderBy('id', 'ASC')->take($per_item)->skip($start)->get();
            //$items_local_count = DB::connection('mysql')->table('agent_transaction')->select('id')->orderBy('id', 'ASC')->take($per_item)->skip($start)->get();

            $items_local_count = DB::connection('mysql')->table('agent_transaction')->select('id')->orderBy('id', 'ASC')->take($per_item)->skip($start)->get();

            $counter = $counter + count($items_local_count->toArray());


            $live_count = count($items_live_count->toArray());
            $local_count = count($items_local_count->toArray());

            if ($items_local_count < $items_live_count) {


                $total_missing = $live_count - $local_count;

                $url = url("importAgentTranscations/$i/$per_item");
                echo "<a href='$url'><small>Agent Transaction Page $i</small></a> <span style='color:red'>Local:  $local_count </span> <span style='color:red'>Live:  $live_count </span><br>";

            } else {

                $url = url("importAgentTranscations/$i/$per_item");
                echo "<a href='$url'><small>Agent Transaction Page $i</small></a> <span style='color:blue'>Local:  $local_count </span> <span style='color:blue'>Live:  $live_count </span><br>";
            }
        }

        $localItems = AgentTransaction::count();

        DB::disconnect('mysql_live');
        DB::disconnect('mysql');

        echo $localItems . " local items | live items : " . $live_item_count . ", items counted on loop " .  $counter;

    }

    public function update($id)
    {
        $items = DB::connection('mysql_live')->select("select * from agent_transaction where id = $id");
        $ctr = 0;

        foreach ($items as $item) {

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
                $agentTransaction = AgentTransaction::where('id', $id)->first();
                $transaction = $agentTransaction->update($data);

                echo "<div style='color:yellow'>$ctr - Added : " . $item->id . " " . $item->created_on . "</div>";
            } else {
                $transaction = AgentTransaction::insert($data);
                echo "<div style='color:blue'>$ctr - Added : " . $item->id . " " . $item->created_on . "</div>";
            }

        }

        echo "done! updating";

    }

    public function showOld($id = null, $per_item = null) 
    {

        set_time_limit(0);

        if ($per_item == null) {
            $per_item = 8000;
        }

        $start = ($id - 1) * ($per_item);
        $end = $id * ($per_item);

        echo $start ." ". $end ."<Br>";

        DB::beginTransaction();

        $ctr = 0;

        for($i = $start; $i<=$end; $i++) 
        {
            $ctr++; 

            $item = DB::connection('mysql_live')->select("select * from agent_transaction where id = $i");         

            if ($item) 
            {          
                $item = $item[0];

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

                $agentTransaction = AgentTransaction::where('id', $item->id)->first();

                try
                {                
                    if ($agentTransaction) 
                    {
                        $transaction = $agentTransaction->update($data);
                        DB::commit();

                        echo "<div style='color:blue'> $ctr | Updated : " . $item->id . " ,  " . $item->created_on . "</div>";

                    } else {
                    
                        $transaction = AgentTransaction::insert($data);
                        DB::commit();

                        echo "<div style='color:blue'> $ctr |  CREATED : " . $item->id . " , " . $item->created_on . "</div>";

                    }                    
                } catch (\Exception $e) {
                    echo "<div style='color:red'>$ctr - Exception Error Found : " . $e->getMessage() . " on Line : " . $e->getLine() . " On Insert</div>";
                }

            } else {
                echo "<div style='color:blue'> $ctr | ID : $i not found </div>";

            }
        }

        DB::disconnect('mysql_live');
        DB::disconnect('mysql');


        echo "<p> done </p>";
    }


    public function show($id = null, $per_item = null)
    {

        set_time_limit(0);

        if ($per_item == null) {
            $per_item = 8000;
        }

        $start = ($id - 1) * ($per_item);
        $end = $id * ($per_item);

        echo "<div>ADDING agent_transaction FROM : " . $start . " - " . $end . "</div>";
        echo "<BR>";

        $items = DB::connection('mysql_live')->table('agent_transaction')->select('*')->orderBy('id', 'ASC')->take($per_item)->skip($start)->get();

        //$items = DB::connection('mysql_live')->select("select * from agent_transaction ORDER BY id ASC LIMIT $per_item OFFSET $start");

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

            DB::beginTransaction();

            $agentTransaction = AgentTransaction::where('id', $item->id)->first();

            if ($agentTransaction) 
            {

                $transaction = $agentTransaction->update($data);
                DB::commit();

                echo "<div style='color:red'>$ctr - UPDATED : " . $item->id . " " . $item->created_on . "</div>";

            } else {


                $transaction = AgentTransaction::insert($data);
                DB::commit();

                echo "<div style='color:red'>$ctr - CREATED : " . $item->id . " " . $item->created_on . "</div>";

             
            }
        }

        echo "success!!! data imported";

    }
}
