<?php

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            [
                'id'         => 1,
                'title'      => 'Admin',
                'created_at' => '2019-09-28 14:22:15',
                'updated_at' => '2019-09-28 14:22:15',
            ],
            [
                'id'         => 2,
                'title'      => 'User',
                'created_at' => '2019-09-28 14:22:15',
                'updated_at' => '2019-09-28 14:22:15',
            ],
            [
                'id'         => 3,
                'title'      => 'Tutor',
                'created_at' => '2019-09-28 14:22:15',
                'updated_at' => '2019-09-28 14:22:15',
            ],
            [
                'id'         => 4,
                'title'      => 'Member',
                'created_at' => '2019-09-28 14:22:15',
                'updated_at' => '2019-09-28 14:22:15',
            ],
            [
                'id'         => 5,
                'title'      => 'Manager',
                'created_at' => '2019-09-28 14:22:15',
                'updated_at' => '2019-09-28 14:22:15',
            ], 
            [
                'id'         => 6,
                'title'      => 'Agent',
                'created_at' => '2019-09-28 14:22:15',
                'updated_at' => '2019-09-28 14:22:15',
            ],                      
        ];

        Role::insert($roles);

    }
}
