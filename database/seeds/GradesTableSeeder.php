<?php

use Illuminate\Database\Seeder;

use App\Models\Grade;

class GradesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $grades = [
            [
                'id'            => 1,
                'name'          => "Standard"
            ],
            [
                'id'            => 2,
                'name'          => "Upgrade"
            ],
            [
                'id'            => 3,
                'name'          => "Platinum"
            ]
        ];

        Grade::insert($grades);        
    }
}
