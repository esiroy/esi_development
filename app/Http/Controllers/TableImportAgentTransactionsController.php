<?php

namespace App\Http\Controllers;

use App\Models\AgentTransaction;
use DB;

class TableImportAgentTransactionsController extends Controller
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
        $items = DB::connection('mysql_live')->table('agent_transaction')->count();

        echo "Live Item count : $items";

        echo "<br>";

        echo "Our Current Item Count : " . AgentTransaction::count();
    }

    public function getNewTransactions()
    {

        $itemLiveArray = null;
        $itemLocalArray = null;

        $items = DB::connection('mysql_live')->table('agent_transaction')->select('id')->orderBy('id', 'desc')->limit(20000)->get();
        foreach ($items as $item) {
            $itemLiveArray[$item->id] = $item->id;
        }

        $localItems = AgentTransaction::select('id')->orderBy('id', 'desc')->limit(20000)->get();
        foreach ($localItems as $item) {
            $itemLocalArray[$item->id] = $item->id;
        }

        $itemDifferences = array_diff($itemLiveArray, $itemLocalArray);

        $differenceCount = count($itemDifferences);

        echo $differenceCount . " are missing in your agent_transaction table<BR>";


        foreach ($itemDifferences as $item) {
            $itemID = $item;

            $liveItems = DB::connection('mysql_live')->select("select * from agent_transaction where id = $itemID");

            $ctr = 0;

            foreach ($liveItems as $liveItem) {
                $ctr = $ctr + 1;
                $data = [
                    'id' => $liveItem->id,
                    'created_at' => $liveItem->created_on,
                    'updated_at' => $liveItem->updated_on,
                    'valid' => $liveItem->valid,
                    'amount' => $liveItem->amount,
                    'remarks' => $liveItem->remarks,
                    'transaction_type' => $liveItem->transaction_type,
                    'agent_id' => $liveItem->agent_id,
                    'created_by_id' => $liveItem->created_by_id,
                    'member_id' => $liveItem->member_id,
                    'schedule_item_id' => $liveItem->schedule_item_id,
                    'price' => $liveItem->price,
                    'lesson_shift_id' => $liveItem->lesson_shift_id,
                    'credits_expiration' => $liveItem->credits_expiration,
                    'old_credits_expiration' => $liveItem->old_credits_expiration,
                ];

                $transaction = AgentTransaction::insert($data);
                echo "<div style='color:blue'>$ctr - Added : " . $liveItem->id . " " . $liveItem->created_on . "</div>";

            }

        }

    }

    public function index()
    {
        $items = DB::connection('mysql_live')->table('agent_transaction')->count();

        echo $items . " on agent transactions<BR>";

        $per_item = 8000;
        $total_pages = ($items / $per_item) + 1;

        for ($i = 1; $i <= $total_pages; $i++) {
            $url = url("importAgentTranscations/import/$i");

            echo "<a href='$url'><small>Transaction Import Page $i</small></a><br>";
        }
    }

    public function updateMember($memberID)
    {

        $items = DB::connection('mysql_live')->select("select * from agent_transaction where member_id = $memberID");

        $ctr = 0;

        AgentTransaction::where('member_id', $memberID)->delete();

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
                $member = AgentTransaction::where('member_id', $memberID)->first();
                $transaction = $member->update($data);
                echo "<div style='color:yellow'>$ctr - Added : " . $item->id . " " . $item->created_on . "</div>";
            } else {
                $transaction = AgentTransaction::insert($data);
                echo "<div style='color:blue'>$ctr - Added : " . $item->id . " " . $item->created_on . "</div>";
            }

        }

        echo "done! updating";

    }


    public function updateAgent($agentID)
    {

        $items = DB::connection('mysql_live')->select("select * from agent_transaction where agent_id = $agentID");

        $ctr = 0;

        AgentTransaction::where('agent_id', $agentID)->delete();

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
                
                'created_by_id' => $item->created_by_id,

                'member_id' => null, //this will not be owned by any member
                'agent_id' => $item->agent_id,

                'schedule_item_id' => $item->schedule_item_id,
                'price' => $item->price,
                'lesson_shift_id' => $item->lesson_shift_id,
                'credits_expiration' => $item->credits_expiration,
                'old_credits_expiration' => $item->old_credits_expiration,
            ];

            if (AgentTransaction::where('id', $item->id)->exists()) {
                $agent = AgentTransaction::where('agent_id', $agentID)->first();
                $transaction = $agent->update($data);

                echo "<div style='color:yellow'>$ctr - Added : " . $item->id . " " . $item->created_on . "</div>";
            } else {
                
                $transaction = AgentTransaction::insert($data);
                echo "<div style='color:blue'>$ctr - Added : " . $item->id . " " . $item->created_on . "</div>";
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

                try
                {
                    $agentTransactionObject = AgentTransaction::where('id', $item->id);
                    $transaction = $agentTransactionObject->update($data);

                    DB::commit();

                    echo "<div style='color:blue'>$ctr - update : " . $item->id . " " . $item->created_on . "</div>";

                } catch (\Exception $e) {

                    echo "<div style='color:red'>$ctr - Exception Error Found : " . $e->getMessage() . " on Line : " . $e->getLine() . " On update </div>";
                }

            } else {

                try
                {
                    $transaction = AgentTransaction::insert($data);

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