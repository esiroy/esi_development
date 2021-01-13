<?php

namespace App\Http\Controllers;

use App\Models\Agent;

use DB;

class TableMemberImporterController extends Controller
{
  
    public function index()
    {
        $items = DB::connection('mysql_live')->table('users_agent')->count();
        $per_item = 8000;
        $total_pages = ($items / $per_item) + 1;

        for ($i = 1; $i <= $total_pages; $i++) {
            $url = url("importAgent/$i");

            echo "<a href='$url'><small>Agent Page $i</small></a><br>";
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

        echo "<div>ADDING agent  FROM : " . $start . " - " . $end . "</div>";
        echo "<BR>";

        
        $items = DB::connection('mysql_live')->select("select * from users_agent ORDER BY id DESC LIMIT $per_item OFFSET $start");

        DB::beginTransaction();

        $ctr = 0;

        foreach ($items as $item) {

            set_time_limit(0);

            $ctr = $ctr + 1;

            $data = [
                'id' => $item->id,
                'agent_id' => $item->agent_id,
                'contract_date' => $item->contract_date,
                'user_id' => $item->user_id,
                'industry_type' => $item->industry_type,
                'management_expense' => $item->management_expense,
                'registration_fee' => $item->registration_fee ,
                'remark' => $item->remark ,
                'representative' => $representative->preferred_gender ,
                'hiragana' => $item->hiragana ,
                'inclination' => $item->inclination ,
                'credits_expiration' => $item->credits_expiration ,
                    
            ];

            if (Agent::where('id', $item->id)->exists()) {
                echo "<div style='color:red'>$ctr - EXISTING : " . $item->id . " " . $item->created_on . "</div>";

                try
                {
                    $UserObj = Agent::where('id', $item->id);

                    $user = $UserObj->update($data);

                    DB::commit();

                    echo "<div style='color:green'>$ctr - updated : " . $item->id . " " . $item->created_on . "</div>";

                } catch (\Exception $e) {

                    echo "<div style='color:red'>$ctr - Exception Error Found : " . $e->getMessage() . " on Line : " . $e->getLine() . " On update </div>";
                }

            } else {

                try
                {
                    $user = Agent::insert($data);

                    $user->agents()->sync([$item->id], false);  

                    DB::commit();

                    echo "<div style='color:blue'>$ctr - added : " . $item->id . " " . $item->firstname . " " . $item->created_on . "</div>";

                } catch (\Exception $e) {

                    echo "<div style='color:red'>$ctr - Exception Error Found : " . $e->getMessage() . " on Line : " . $e->getLine() . " On Insert</div>";
                }

            }
        }

        echo "success!!! data imported";

    }
}
