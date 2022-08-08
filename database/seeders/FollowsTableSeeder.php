<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class FollowsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('follows')->delete();
        
        \DB::table('follows')->insert(array (
            0 => 
            array (
                'id' => 1,
                'follower_id' => 1,
                'following' => 5,
                'created_at' => '2022-07-15 02:44:29',
                'updated_at' => '2022-07-15 02:44:29',
            ),
            1 => 
            array (
                'id' => 2,
                'follower_id' => 5,
                'following' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}