<?php

use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\Tutor;
use App\Models\Role;


class TutorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(User $user)
    {
        $userData = [           
                'id'             => 2,
                'first_name'     => 'tutor',
                'last_name'      => 'tutor',
                'email'          => 'tutor@tutor.com',
                'username'       => 'tutor@tutor.com',
                'password'       => '$2y$10$6an7csz5VY5vq/0qw/VJ0.YX4u4bHl6QKeoJT.Cqc.nncudsc70Hi',
                'remember_token' => null,
                'api_token'      => Hash('sha256', Str::random(80)),
                'created_at'     => '2019-09-28 14:22:15',
                'updated_at'     => '2019-09-28 14:22:15'
        ];        
        $user = User::create($userData);

        //Add Role
        $roles[] = Role::where('title', 'Tutor')->first()->id;
        $user->roles()->sync($roles); 
        
        $tutorData = [
            'id'             => 1,
            "user_id" => "2",
            "sort" => "1",         
            "salary_rate" => "15000",
            "member_grade_id" => "1",
            "skype_name" => "tutor@tutor.com",
            "skype_id" => "tutor@tutor.com",
            "name_en" => "Veronica",
            "name_jp" => "(Soho, Son Jessica Gozia)",
            "gender" => "male",
            "hobby" => "none",
            "birthdate" => "1983-09-28",
            "major_in" => "test",
            "introduction" => "hi ",
            "shift_id" => "1",
            "japanese_fluency_id" => "1",
            "is_default_main_tutor" => true,
        ];
        $tutor = Tutor::create($tutorData);
        $user->tutors()->sync([$tutor->id], false);
        
        //User::findOrFail(2)->roles()->sync(3); //find user 2, with roles (3)
    }
}
