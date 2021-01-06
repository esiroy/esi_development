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
                'id'            => 2,
                'name'          => "40 minutes",
                'value'         => 40
            ],
            [
                'id'            => 3,
                'name'          => "Afternoon Shift 2",
                'value'         => 30
            ],                    
            [
                'id'            => 4,
                'name'          => "25 Minutes",
                'value'         => 25
            ],
            [
                'id'            => 5,
                'name'          => "40 mins",
                'value'         => 40
            ]            
        ];

        
        Shift::insert($shifts);    

    }
}
