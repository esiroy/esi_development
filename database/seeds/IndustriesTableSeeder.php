<?php

use Illuminate\Database\Seeder;

use App\Models\Industry;

class IndustriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $industries = [
            [
                'id'            => 1,
                'name'          => "Private School",
                'value'         => "PRIVATE_SCHOOL"
            ],
            [
                'id'            => 2,
                'name'          => "Public School",
                'value'         => "PRIVATE_SCHOOL"
            ],
            [
                'id'            => 3,
                'name'          => "Company",
                'value'         => "COMPANY"
            ],
            [
                'id'            => 4,
                'name'          => "Individual",
                'value'         => "INDIVIDUAL"
            ]            
        ];

        Industry::insert($industries);   
    }
}
