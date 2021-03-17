<?php

namespace App\Http\Controllers;

use App\Models\User;

use App\Models\UserImporter;

use App\Models\Role;

use DB;

class TableUserImporterController extends Controller
{
  
    public function index($per_item = null)
    {
        $items = DB::connection('mysql_live')->table('users')->count();

        if ($per_item == null) {
            $per_item = 8000;
        }
       
        $total_pages = ($items / $per_item) + 1;

        for ($i = 1; $i <= $total_pages; $i++) {

            
            $url = url("importUsers/$i/$per_item");

            echo "<a href='$url'><small>User Page $i</small></a><br>";
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

        echo "<div>ADDING user  FROM : " . $start . " - " . $end . "</div>";
        echo "<BR>";

        
        $items = DB::connection('mysql_live')->select("select * from users ORDER BY id ASC LIMIT $per_item OFFSET $start");

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
                'activation_code' => $item->activation_code,
                'email' => $item->email,
                'firstname' => $item->firstname,
                'is_activated' => $item->is_activated,
                'last_login' => $item->last_login,
                'lastname' => $item->lastname,
                'password' => $item->password,
                'user_type' => $item->user_type,
                'username' => $item->username,
                'japanese_lastname' => $item->japanese_lastname,
                'japanese_firstname' => $item->japanese_firstname,
                'email_notification' => $item->email_notification,
                'items_per_page' => $item->items_per_page,
                'no_page_item' => $item->no_page_item,
                'company_id' => $item->company_id,
                'reset_password_code' => $item->reset_password_code,
                'is_japanese' => $item->is_japanese,
            ];

            

            if (User::where('id', $item->id)->exists()) {
                echo "<div style='color:red'>$ctr - EXISTING : " . $item->id . " " . $item->created_on . "</div>";

                try
                {

                    $UserObj = UserImporter::where('id', $item->id)->first();

                    $user = $UserObj->update($data);

                    DB::commit();

                    echo "<div style='color:green'>$ctr - updated : " . $item->id . " " . $item->firstname . " " . $item->created_on . "</div>";

                } catch (\Exception $e) {

                    echo "<div style='color:red'>$ctr - Exception Error Found : " . $e->getMessage() . " on Line : " . $e->getLine() . " On update </div>";
                }

            } else {

                try
                {
                    $user = User::create($data);

                    $roles = [];
                    

                    if ($item->user_type == "ADMINISTRATOR") 
                    {

                        $roles[] = Role::where('title', 'Admin')->first()->id;                    
                        $user->roles()->sync($roles);           

                    } else if  ($item->user_type == "MANAGER") {               

                        $roles[] = Role::where('title', 'Manager')->first()->id;                    
                        $user->roles()->sync($roles);                            

                    } else if ($item->user_type == "MEMBER") {

                        $roles[] = Role::where('title', 'Member')->first()->id;                    
                        $user->roles()->sync($roles);   

                    } else if ($item->user_type == "AGENT") {

                        $roles[] = Role::where('title', 'Agent')->first()->id;                    
                        $user->roles()->sync($roles);      

                    } else if ($item->user_type == "TUTOR") {

                        $roles[] = Role::where('title', 'Tutor')->first()->id;                    
                        $user->roles()->sync($roles);     

                    }
                    
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
