<?php

use Illuminate\Database\Seeder;

use App\Models\Membership;


class MembershipsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $membership = [
            [
                'id'            => 1,
                'name'          => "Point Balance"
            ],
            [
                'id'            => 2,
                'name'          => "Monthly"
            ],
            [
                'id'            => 3,
                'name'          => "Both"
            ]
        ];

        Membership::insert($membership);                  
    }
}
