<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PostLikesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('post_likes')->delete();
        
        \DB::table('post_likes')->insert(array (
            0 => 
            array (
                'id' => 1,
                'user_id' => 7,
                'post_id' => 1,
                'type' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'user_id' => 1,
                'post_id' => 1,
                'type' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}