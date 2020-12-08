<?php

use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\Member;
use App\Models\Tutor;
use App\Models\Role;

class MemberTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userData = [           
            'id'             => 3,
            'first_name'     => 'abellana',
            'last_name'      => 'abellana',
            'email'          => 'abellana@gmail.com',
            'username'       => 'abellana@gmail.com',
            'password'       => '$2y$10$6an7csz5VY5vq/0qw/VJ0.YX4u4bHl6QKeoJT.Cqc.nncudsc70Hi',
            'remember_token' => null,
            'api_token'      => Hash('sha256', Str::random(80)),
            'created_at'     => '2019-09-28 14:22:15',
            'updated_at'     => '2019-09-28 14:22:15'
        ];        
        $user = User::create($userData);

        //Add Role
        $roles[] = Role::where('title', 'Member')->first()->id;
        $user->roles()->sync($roles); 
    
        $memberInformation =
        [
            'user_id'                   =>  $user->id,
            'member_attribute_id'       =>  2, //attribute 1-trial, 2-member, 3-widthraw
            'nickname'                  =>  'abellana',
            'agent_id'                  =>  1,
            'gender'                    =>  'male',
            'birthdate'                 =>  date('Y-m-d', strtotime('1983-4-17')),
            'age'                       =>  37,
            'communication_app_name'    => "Skype",
            'communication_app_username' => "abellana@gmail.com",
            'membership_id'             =>  3,                    
            'exam_record_id'            =>  null, //@todo: remove exam or nullify
            'member_since'              =>  date('Y-m-d', strtotime('2010-1-31')),
            'lesson_time_id'            =>  1, //1-25 minutes ,
            'main_tutor_id'             =>  2,
            'agent_report_card'         =>  (boolean) 1,
            'agent_monthly_report'      =>  (boolean) 1,
            'member_report_card'        =>  (boolean) 1,
            'member_monthly_report'     =>  (boolean) 1,
            'point_purchase'            =>  'Agent',
            
        ];
        $member = Member::create($memberInformation);
        $user->members()->sync([$member->id], false);  
    }
}
