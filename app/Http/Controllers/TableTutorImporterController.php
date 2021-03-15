<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Agent;
use App\Models\Tutor;

use DB;

class TableTutorImporterController extends Controller
{
  
    public function index()
    {
        $items = DB::connection('mysql_live')->table('users_tutor')->count();
        $per_item = 8000;
        $total_pages = ($items / $per_item) + 1;

        for ($i = 1; $i <= $total_pages; $i++) {
            $url = url("importTutors/$i");

            echo "<a href='$url'><small>Tutor Page $i</small></a><br>";
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

        echo "<div>ADDING Tutor  FROM : " . $start . " - " . $end . "</div>";
        echo "<BR>";

        
        $items = DB::connection('mysql_live')->select("select * from users_tutor ORDER BY user_id ASC LIMIT $per_item OFFSET $start");

        DB::beginTransaction();

        $ctr = 0;

        foreach ($items as $item) {

            set_time_limit(0);

            $ctr = $ctr + 1;

            $data = [
                //'id' => $item->id,
                'fluency' => $item->fluency,
                'gender' => $item->gender,
                'grade' => $item->grade,
                'hobby' => $item->hobby,                
                'introduction' => $item->introduction,                
                'salary_rate' => $item->salary_rate,
                'sort' => $item->sort,
                'user_id' => $item->user_id,
                'birthday' => $item->birthday,
                'skype_id' => $item->skype_id,
                'skype_name' => $item->skype_name,
                'skype_password' => $item->skype_password,
                'lesson_shift_id' => $item->lesson_shift_id,
                'is_default_main_tutor' => $item->is_default_main_tutor,
                'is_default_support_tutor' => $item->is_default_support_tutor,
                'is_terminated' => $item->is_terminated,
            ];

            if (Tutor::where('user_id', $item->user_id)->exists()) 
            {
                echo "<div style='color:red'>$ctr - EXISTING : " . $item->user_id . "</div>";

                try
                {
                    $tutorObj = Tutor::where('user_id', $item->user_id);

                    $tutor = $tutorObj->update($data);

                    DB::commit();

                    echo "<div style='color:green'>$ctr - updated : " . $item->user_id  . "</div>";

                } catch (\Exception $e) {

                    echo "<div style='color:red'>$ctr - Exception Error Found : " . $e->getMessage() . " on Line : " . $e->getLine() . " On update </div>";
                }

            } else {

                try
                {
                    $tutor = Tutor::create($data);

                    //$tutor->tutors()->sync([$tutor->user_id], false);  

                    $user = User::find($item->user_id);

                    $user->tutors()->sync([$tutor->id], false);                      

                    DB::commit();

                    echo "<div style='color:blue'>$ctr - added : " . $item->user_id  . "</div>";

                } catch (\Exception $e) {

                    echo "<div style='color:red'>$ctr - Exception Error Found : " . $e->getMessage() . " on Line : " . $e->getLine() . " On Insert</div>";
                }

            }
        }

        echo "success!!! data imported";

    }
}
