<?php

use Illuminate\Database\Seeder;

use App\Models\Shift;

class ShiftsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $shifts = [
            [           
                'id'            => 1,
                'name'          => "25 Minutes",
                'value'         => 25,
            ],
            [
                'id'            => 2,
                'name'          => "40 Minutes",
                'value'         => 40
            ]
        ];

        
        Shift::insert($shifts);    

    }
}
