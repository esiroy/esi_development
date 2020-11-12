<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'id'             => 1,
                'first_name'     => 'Admin',
                'last_name'      => 'Account',

                'first_name_jp'     => 'Admin',
                'last_name_jp'      => 'Account',
                
                
                'email'          => 'admin@admin.com',
                'username'       => 'admin@admin.com',
                'password'       => '$2y$10$6an7csz5VY5vq/0qw/VJ0.YX4u4bHl6QKeoJT.Cqc.nncudsc70Hi',
                'remember_token' => null,
                'api_token'      => Hash('sha256', Str::random(80)),
                'created_at'     => '2019-09-28 14:22:15',
                'updated_at'     => '2019-09-28 14:22:15',
            ],
        ];

        User::insert($users);

    }
}
