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
                'id'            => 4,
                'name'          => "25 Minutes",
                'value'         => 25
            ]
        ];

        
        Shift::insert($shifts);    

    }
}
