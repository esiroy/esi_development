<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\User;
use DB;

class TableAgentImporterController extends Controller
{
  
    public function index()
    {
        $items = DB::connection('mysql_live')->table('users_agent')->count();

        echo "<div>there are ". $items ." agents</div>";
                
        $per_item = 8000;
        $total_pages = ($items / $per_item) + 1;

        for ($i = 1; $i <= $total_pages; $i++) {
            $url = url("importAgents/$i");

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

        
        $items = DB::connection('mysql_live')->select("select * from users_agent ORDER BY user_id DESC LIMIT $per_item OFFSET $start");

        DB::beginTransaction();

        $ctr = 0;

        foreach ($items as $item) {

            set_time_limit(0);

            $ctr = $ctr + 1;

            $data = [
                //'id' => $item->id,
                'agent_id' => $item->agent_id,
                'contract_date' => $item->contract_date,
                'user_id' => $item->user_id,
                'industry_type' => $item->industry_type,
                'management_expense' => $item->management_expense,
                'registration_fee' => $item->registration_fee ,
                'remark' => $item->remark ,
                'representative' => $item->representative,
                'hiragana' => $item->hiragana ,
                'inclination' => $item->inclination ,
                'credits_expiration' => $item->credits_expiration ,
                    
            ];

            if (Agent::where('user_id', $item->user_id)->exists()) {

                echo "<div style='color:red'>$ctr - EXISTING : " . $item->user_id . " " . $item->agent_id . "</div>";

                try
                {
                    $UserObj = Agent::where('user_id', $item->user_id);

                    $user = $UserObj->update($data);

                    DB::commit();                    

                    echo "<div style='color:green'>$ctr - updated : user id : " . $item->user_id . "   ". $item->agent_id ."</div>";

                } catch (\Exception $e) {

                    echo "<div style='color:red'>$ctr - Exception Error Found : " . $e->getMessage() . " on Line : " . $e->getLine() . " On update </div>";
                }

            } else {

                try
                {
                    $agent = Agent::create($data);

                    $user = User::find($item->user_id);

                    $user->agents()->sync([$agent->id], false); 

                    DB::commit();

                    echo "<div style='color:blue'>$ctr - added : user id : " . $item->user_id . "   ". $item->agent_id ."</div>";

                } catch (\Exception $e) {

                    echo "<div style='color:red'>$ctr - Exception Error Found : " . $e->getMessage() . " on Line : " . $e->getLine() . " On Insert</div>";
                }

            }
        }

        echo "success!!! data imported";

    }
}
