<?php

use Illuminate\Database\Seeder;

use App\Models\Attribute;


class AttributeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $attribute = [
            [
                'id'            => 1,
                'name'          => "Trial"
            ],
            [
                'id'            => 2,
                'name'          => "Member"
            ],
            [
                'id'            => 3,
                'name'          => "Withdraw"
            ]
        ];
        
        Attribute::insert($attribute);    
    }
}
