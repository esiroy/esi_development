<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Member;
use App\Models\UserImporter;
use App\Models\Role;
use DB;

class TableUserExpiryUpdaterController extends Controller
{
  
    public function index($per_item = null)
    {
        
        $per_item = 8000;
                
        $dateFrom = date('Y-m-d', strtotime('2021-05-10'));

          

        $items = DB::connection('mysql_live')                    
                    ->table('users')
                    //->where('last_login', '>=', $dateFrom ." 01:00:00")
                    ->get();
       
      
        foreach ($items as $item) 
        {
            //search $item->id it on our system and check if the (current) expiration date is not equal or is it before the expiration date of live system (old)

            $member = Member::where('user_id', $item->id)->first();

            if ($member) {

                $live_member = DB::connection('mysql_live')->table('users_member')->where('user_id', $item->id)->first();

                if ($live_member) 
                {

                    $current_member_expiry = date('Y-m-d', strtotime($member->credits_expiration));
                    $live_member_expiry = date('Y-m-d', strtotime($live_member->credits_expiration));

    
                    if ($current_member_expiry < $live_member_expiry) 
                    {    

                        $link = url('admin/member/account/'.$item->id);

                        echo "<a href='$link' style='color:red'>". $current_member_expiry  ." !== ".  $live_member_expiry ."</a>";

                        echo "<br>";

                    } else {
    
                        //echo  $current_member_expiry  ." == ".  $live_member_expiry;
                    }             
    
                } else {
    
                    echo $item->id ." does not exists ";                
                }

            } else {
                echo "<div>Member " . $item->id ." was not found on current live system</div>";
            }
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
