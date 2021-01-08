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

    public function importAgentTranscations($id = null)
    {
        set_time_limit(0);

        $start = ($id - 1) * 100000;
        $end = $id * 100000;

        echo "<div>ADDING agent_transcations FROM : ". $start ." - ". $end ."</div>";

        echo "<BR>";

        $items = DB::connection('mysql_live')->select("select * from agent_transaction limit $start, $end");

        foreach ($items as $item) {

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

            $agent = AgentTransaction::find($item->id);

            if (isset($agent->id)) {
                echo "<div style='color:red'>EXISTING : " . $item->id . " " . $item->created_on . "</div>";
            } else {
                $transaction = AgentTransaction::create($data);
                echo "<div style='color:blue'>Added : " . $item->id . " " . $item->created_on . "</div>";
            }

        }

    }
}
