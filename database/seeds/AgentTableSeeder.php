<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Member;
use App\Models\Agent;
use App\Models\Role;

class AgentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /************************************************
                        AGENT 01 
        *************************************************/        
        $userData = [           
            'id'             => 10,
            'first_name'     => 'agent',
            'last_name'      => 'agent',
            'email'          => 'agent01@agent.com',
            'username'       => 'agent01',
            'password'       => '$2y$10$6an7csz5VY5vq/0qw/VJ0.YX4u4bHl6QKeoJT.Cqc.nncudsc70Hi',
            'remember_token' => null,
            'api_token'      => Hash('sha256', Str::random(80)),
            'created_at'     => '2019-09-28 14:22:15',
            'updated_at'     => '2019-09-28 14:22:15'
        ];        
        $user = User::create($userData);

        //Add Role
        $roles[] = Role::where('title', 'Agent')->first()->id;
        $user->roles()->sync($roles); 
    
        $agentInformation =
        [
            'user_id'                   =>  $user->id,
            'industry_type_id'          => 1,
            'name_en'                   => "Agent 0001",
            'name_jp'                   => "Japanese agent 0001",
            'representative'            => "test",
            'hiragana'                  => "test",
            'address'                   => "test",
            'inclination'               => "test",
            'contract_date'             => date('Y-m-d', strtotime('2010-1-31')),
            'agent_remark'              => "No remark here...",
            'is_terminated'             =>  0,
            'initial_date_of_purchase'  =>  date('Y-m-d', strtotime('2010-1-31')),
            'purchased_amount'          =>  100,
            'credits'                   =>  100,
            'credits_expiration'        =>	date('Y-m-d G:i:s', strtotime('+6 months')),
            'latest_purchase_date'      =>  date('Y-m-d', strtotime('2010-1-31')) 
        ];

        $agent = Agent::create($agentInformation);
        $user->agents()->sync([$agent->id], false);

        /************************************************
                        AGENT 02 
        *************************************************/
        $userData = [           
            'id'             => 11,
            'first_name'     => 'mobile agent 00002',
            'last_name'      => 'mobile (japanese) agent 00002',
            'email'          => 'agent0002@agent.com',
            'username'       => 'agent02@agent.com',
            'password'       => '$2y$10$6an7csz5VY5vq/0qw/VJ0.YX4u4bHl6QKeoJT.Cqc.nncudsc70Hi',
            'remember_token' => null,
            'api_token'      => Hash('sha256', Str::random(80)),
            'created_at'     => '2019-09-28 14:22:15',
            'updated_at'     => '2019-09-28 14:22:15'
        ];        
        $user = User::create($userData);

        //Add Role
        $roles[] = Role::where('title', 'Agent')->first()->id;
        $user->roles()->sync($roles); 
    
        $agentInformation =
        [
            'user_id'                   =>  $user->id,
            'industry_type_id'          => 1,
            'name_en'                   => "Agent 00002",
            'name_jp'                   => "Japanese agent 00002",
            'representative'            => "Agent 00002",
            'hiragana'                  => "Agent 00002",
            'address'                   => "Agent 00002",
            'inclination'               => "Agent 00002",
            'contract_date'             => date('Y-m-d', strtotime('2010-1-31')),
            'agent_remark'              => "no remark",
            'is_terminated'             =>  0,
            'initial_date_of_purchase'  =>  date('Y-m-d', strtotime('2010-1-31')),
            'purchased_amount'          =>  100,
            'credits'                   =>  100,
            'credits_expiration'        =>	date('Y-m-d G:i:s', strtotime('+6 months')),
            'latest_purchase_date'      =>  date('Y-m-d', strtotime('2010-1-31')) 
        ];

        $agent = Agent::create($agentInformation);
        $user->agents()->sync([$agent->id], false);          
    }
}
