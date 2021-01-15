<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Member;
use App\Models\Role;

use DB;

class TableMemberImporterController extends Controller
{
  
    public function index($per_item = null)
    {
        $items = DB::connection('mysql_live')->table('users_member')->count();

        if ($per_item == null) {
            $per_item = 8000;
        }

        $total_pages = ($items / $per_item) + 1;

        for ($i = 1; $i <= $total_pages; $i++) {
            $url = url("importMembers/$i/$per_item");

            echo "<a href='$url'><small>Member Page $i</small></a><br>";
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

        echo "<div>ADDING member  FROM : " . $start . " - " . $end . "</div>";
        echo "<BR>";

        
        $items = DB::connection('mysql_live')->select("select * from users_member ORDER BY user_id DESC LIMIT $per_item OFFSET $start");

        DB::beginTransaction();

        $ctr = 0;

        foreach ($items as $item) {

            set_time_limit(0);

            $ctr = $ctr + 1;

            $data = [
                //'id' => $item->id,
                //'created_at' => $item->created_on,
                //'updated_at' => $item->updated_on,
                //'valid' => $item->valid,
                'user_id' => $item->user_id,
                'hobby' => $item->hobby,
                'level' => $item->level,
                'preferred_tutor_character' => $item->preferred_tutor_character ,
                'preferred_tutor_experience' => $item->preferred_tutor_experience ,
                'preferred_gender' => $item->preferred_gender ,
                'purpose' => $item->purpose ,
                'student_year' => $item->student_year ,
                'preferred_support_type' => $item->preferred_support_type ,
                'year' => $item->year ,
                'tutor_id' => $item->tutor_id ,
                'age' => $item->age ,
                'attribute' => $item->attribute ,
                'birthday' => $item->birthday ,
                'member_since' => $item->member_since ,
                'nickname' => $item->nickname ,
                'skype_account' => $item->skype_account ,
                'gender' => $item->gender ,
                'agent_id' => $item->agent_id ,
                'lesson_shift_id' => $item->lesson_shift_id ,
                'course_category_id' => $item->course_category_id ,
                'course_item_id' => $item->course_item_id ,
                'english_level' => $item->english_level ,
                'is_monthly_report_card_visible' => $item->is_monthly_report_card_visible ,     
                'is_monthly_report_card_visible_to_agent' => $item->is_monthly_report_card_visible_to_agent ,     
                'is_report_card_visible' => $item->is_report_card_visible ,     
                'is_report_card_visible_to_agent' => $item->is_report_card_visible_to_agent ,     
                'point_purchase_type' => $item->point_purchase_type ,     
                'credits_expiration' => $item->credits_expiration ,     
                'membership' => $item->membership ,     
                'communication_app' => $item->communication_app ,     
                'zoom_account' => $item->zoom_account ,     
                'no_of_active_reserve' => $item->no_of_active_reserve,     
                'no_of_active_reserve_left' => $item->no_of_active_reserve_left                
            ];

            if (Member::where('user_id', $item->user_id)->exists()) {

                echo "<div style='color:red'>$ctr - EXISTING : " . $item->user_id . "</div>";

                try
                {
                    $UserObj = Member::where('user_id', $item->user_id)->first();

                    $user = $UserObj->update($data);

                    DB::commit();

                    echo "<div style='color:green'>$ctr - updated : " . $item->user_id . "</div>";

                } catch (\Exception $e) {

                    echo "<div style='color:red'>". $item->user_id . " " . $ctr ." - Exception Error Found : ( Member Update ) " . $e->getMessage() . " on Line : " . $e->getLine() . " On update </div>";
                }

            } else {

                try
                {
                    $member = Member::create($data);

                    //$member->members()->sync([$member->id], false);  

                    DB::commit();

                    echo "<div style='color:blue'>$ctr - added : " . $item->user_id . "</div>";

                } catch (\Exception $e) {

                    
                    echo "<div style='color:red'>" . $item->user_id . " -".$ctr  ." - Exception Error Found : (Member Insert) " . $e->getMessage() . " on Line : " . $e->getLine() . " On Insert <BR></div> <br>";
                }

            }
        }

        echo "success!!! data imported";

    }
}
