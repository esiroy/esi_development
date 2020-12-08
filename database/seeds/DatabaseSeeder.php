<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UserSeeder::class);
        $this->call([
            PermissionsTableSeeder::class,
            RolesTableSeeder::class,
            PermissionRoleTableSeeder::class,
            UsersTableSeeder::class,
            RoleUserTableSeeder::class,
            GradesTableSeeder::class,            
            IndustriesTableSeeder::class,
            ShiftsTableSeeder::class,
            AttributeTableSeeder::class,
            MembershipsTableSeeder::class,            
            TutorTableSeeder::class,
            MemberTableSeeder::class,
            AgentTableSeeder::class,
        ]);
    }
}
