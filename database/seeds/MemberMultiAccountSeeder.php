<?php

use Illuminate\Database\Seeder;

class MemberMultiAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('member_multi_account')->insert([
            [
                'name' => 'Account 1',
                'description' => 'First account description',
                'sequence_number' => 1,
                'is_default' => 1,
                'valid' => 1
            ],
            [
                'name' => 'Account 2',
                'description' => 'Second account description',
                'sequence_number' => 2,
                'is_default' => 0,
                'valid' => 1
            ],
            [
                'name' => 'Account 3',
                'description' => 'Third account description',
                'sequence_number' => 3,
                'is_default' => 0,
                'valid' => 1
            ],
            [
                'name' => 'Account 4',
                'description' => 'Fourth account description',
                'is_default' => 0,
                'sequence_number' => 4,
                'valid' => 1
            ],                     
            // Add more data as needed
        ]);
    }
}
