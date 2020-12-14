<?php

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            [
                'id'         => '1',
                'title'      => 'permission_create',
                'created_at' => '2019-09-28 14:22:15',
                'updated_at' => '2019-09-28 14:22:15',
            ],
            [
                'id'         => '2',
                'title'      => 'permission_edit',
                'created_at' => '2019-09-28 14:22:15',
                'updated_at' => '2019-09-28 14:22:15',
            ],
            [
                'id'         => '3',
                'title'      => 'permission_show',
                'created_at' => '2019-09-28 14:22:15',
                'updated_at' => '2019-09-28 14:22:15',
            ],
            [
                'id'         => '4',
                'title'      => 'permission_delete',
                'created_at' => '2019-09-28 14:22:15',
                'updated_at' => '2019-09-28 14:22:15',
            ],
            [
                'id'         => '5',
                'title'      => 'permission_access',
                'created_at' => '2019-09-28 14:22:15',
                'updated_at' => '2019-09-28 14:22:15',
            ],
            [
                'id'         => '6',
                'title'      => 'role_create',
                'created_at' => '2019-09-28 14:22:15',
                'updated_at' => '2019-09-28 14:22:15',
            ],
            [
                'id'         => '7',
                'title'      => 'role_edit',
                'created_at' => '2019-09-28 14:22:15',
                'updated_at' => '2019-09-28 14:22:15',
            ],
            [
                'id'         => '8',
                'title'      => 'role_show',
                'created_at' => '2019-09-28 14:22:15',
                'updated_at' => '2019-09-28 14:22:15',
            ],
            [
                'id'         => '9',
                'title'      => 'role_delete',
                'created_at' => '2019-09-28 14:22:15',
                'updated_at' => '2019-09-28 14:22:15',
            ],
            [
                'id'         => '10',
                'title'      => 'role_access',
                'created_at' => '2019-09-28 14:22:15',
                'updated_at' => '2019-09-28 14:22:15',
            ],
            [
                'id'         => '11',
                'title'      => 'user_management_access',
                'created_at' => '2019-09-28 14:22:15',
                'updated_at' => '2019-09-28 14:22:15',
            ],
            [
                'id'         => '12',
                'title'      => 'user_create',
                'created_at' => '2019-09-28 14:22:15',
                'updated_at' => '2019-09-28 14:22:15',
            ],
            [
                'id'         => '13',
                'title'      => 'user_edit',
                'created_at' => '2019-09-28 14:22:15',
                'updated_at' => '2019-09-28 14:22:15',
            ],
            [
                'id'         => '14',
                'title'      => 'user_show',
                'created_at' => '2019-09-28 14:22:15',
                'updated_at' => '2019-09-28 14:22:15',
            ],
            [
                'id'         => '15',
                'title'      => 'user_delete',
                'created_at' => '2019-09-28 14:22:15',
                'updated_at' => '2019-09-28 14:22:15',
            ],
            [
                'id'         => '16',
                'title'      => 'user_access',
                'created_at' => '2019-09-28 14:22:15',
                'updated_at' => '2019-09-28 14:22:15',
            ],
            //admin access
            [
                'id'         => '17',
                'title'      => 'admin_access',
                'created_at' => '2019-09-28 14:22:15',
                'updated_at' => '2019-09-28 14:22:15',
            ],
            //start module
            [
                'id'         => '18',
                'title'      => 'module_access',
                'created_at' => '2019-09-28 14:22:15',
                'updated_at' => '2019-09-28 14:22:15',
            ],
            [
                'id'         => '19',
                'title'      => 'filemanager_access',
                'created_at' => '2019-09-28 14:22:15',
                'updated_at' => '2019-09-28 14:22:15',
            ],
            [
                'id'         => '20',
                'title'      => 'filemanager_create',
                'created_at' => '2019-09-28 14:22:15',
                'updated_at' => '2019-09-28 14:22:15',
            ],
            [
                'id'         => '21',
                'title'      => 'filemanager_edit',
                'created_at' => '2019-09-28 14:22:15',
                'updated_at' => '2019-09-28 14:22:15',
            ],
            [
                'id'         => '22',
                'title'      => 'filemanager_show',
                'created_at' => '2019-09-28 14:22:15',
                'updated_at' => '2019-09-28 14:22:15',
            ],
            [
                'id'         => '23',
                'title'      => 'filemanager_delete',
                'created_at' => '2019-09-28 14:22:15',
                'updated_at' => '2019-09-28 14:22:15',
            ],
            [
                'id'         => '24',
                'title'      => 'filemanager_upload',
                'created_at' => '2019-09-28 14:22:15',
                'updated_at' => '2019-09-28 14:22:15',
            ],
            [
                'id'         => '25',
                'title'      => 'filemanager_upload_delete',
                'created_at' => '2019-09-28 14:22:15',
                'updated_at' => '2019-09-28 14:22:15',
            ],
            [
                'id'         => '26',
                'title'      => 'filemanager_admin',
                'created_at' => '2019-09-28 14:22:15',
                'updated_at' => '2019-09-28 14:22:15',
            ],
            [
                'id'         => '27',
                'title'      => 'filemanager_share',
                'created_at' => '2019-09-28 14:22:15',
                'updated_at' => '2019-09-28 14:22:15',
            ],
            [
                'id'         => '28',
                'title'      => 'filemanager_upload_share',
                'created_at' => '2019-09-28 14:22:15',
                'updated_at' => '2019-09-28 14:22:15',
            ],
            [
                'id'         => '29',
                'title'      => 'formbuilder_access',
                'created_at' => '2019-09-28 14:22:15',
                'updated_at' => '2019-09-28 14:22:15',
            ],
            [
                'id'         => '30',
                'title'      => 'admin_lesson_scheduler_access',
                'created_at' => '2019-09-28 14:22:15',
                'updated_at' => '2019-09-28 14:22:15',
            ],
            [
                'id'         => '31',
                'title'      => 'tutor_lesson_scheduler_access',
                'created_at' => '2019-09-28 14:22:15',
                'updated_at' => '2019-09-28 14:22:15',
            ]                       


        ];

        
        Permission::insert($permissions);

    }
}
