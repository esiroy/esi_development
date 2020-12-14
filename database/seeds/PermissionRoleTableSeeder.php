<?php

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionRoleTableSeeder extends Seeder
{
    public function run()
    {
        $admin_permissions = Permission::all();
        Role::findOrFail(1)->permissions()->sync($admin_permissions->pluck('id'));

        //user (2)
        $user_permissions = $admin_permissions->filter(function ($permission) {
            return substr($permission->title, 0, 5) != 'user_' && substr($permission->title, 0, 5) != 'role_' && substr($permission->title, 0, 11) != 'permission_';
        });
        Role::findOrFail(2)->permissions()->sync($user_permissions);



        //tutor (3)
        $tutor_perm = $admin_permissions->filter(function ($permission) {
            return substr($permission->title, 0, 5) != 'user_' 
                    && substr($permission->title, 0, 5) != 'role_'                     
                    && substr($permission->title, 0, 6) != 'member_' 
                    && substr($permission->title, 0, 13) != 'admin_lesson_' 
                    && substr($permission->title, 0, 11) != 'permission_';
        });
        Role::findOrFail(3)->permissions()->sync($tutor_perm);        
        
        

        //member (4)
        $member_permissions = $admin_permissions->filter(function ($permission) {
            return substr($permission->title, 0, 5) != 'user_'
            && substr($permission->title, 0, 5) != 'role_'                     
            && substr($permission->title, 0, 13) != 'admin_lesson_'
            && substr($permission->title, 0, 11) != 'permission_' 
            && substr($permission->title, 0, 5);
        });
        Role::findOrFail(4)->permissions()->sync($member_permissions);
        

        //manager
        $manager_permissions = $admin_permissions->filter(function ($permission) {
            return substr($permission->title, 0, 5) != 'user_'
            && substr($permission->title, 0, 5) != 'role_'    
            && substr($permission->title, 0, 6) != 'member_'                 
            && substr($permission->title, 0, 13) != 'admin_lesson_'
            && substr($permission->title, 0, 11) != 'permission_';            
        });
        Role::findOrFail(5)->permissions()->sync($manager_permissions);


        //manager
        $agent_permissions = $admin_permissions->filter(function ($permission) {
            return substr($permission->title, 0, 5) != 'user_'
            && substr($permission->title, 0, 5) != 'role_'    
            && substr($permission->title, 0, 6) != 'member_'                 
            && substr($permission->title, 0, 13) != 'admin_lesson_'
            && substr($permission->title, 0, 11) != 'permission_';            
        });
        Role::findOrFail(5)->permissions()->sync($agent_permissions);        

        

    }
}
