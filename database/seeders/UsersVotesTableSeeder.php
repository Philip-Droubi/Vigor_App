<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UsersVotesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('users_votes')->delete();
        
        \DB::table('users_votes')->insert(array (
            0 => 
            array (
                'id' => 1,
                'user_id' => 1,
                'vote_id' => 5,
                'created_at' => '2022-07-14 23:40:12',
                'updated_at' => '2022-07-14 23:42:39',
            ),
            1 => 
            array (
                'id' => 2,
                'user_id' => 5,
                'vote_id' => 4,
                'created_at' => '2022-07-14 23:41:31',
                'updated_at' => '2022-07-14 23:42:27',
            ),
        ));
        
        
    }
}