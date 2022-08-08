<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PostsVotesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('posts_votes')->delete();
        
        \DB::table('posts_votes')->insert(array (
            0 => 
            array (
                'id' => 1,
                'post_id' => 4,
                'vote' => 'Agree',
                'created_at' => '2022-07-14 23:39:16',
                'updated_at' => '2022-07-14 23:39:16',
            ),
            1 => 
            array (
                'id' => 2,
                'post_id' => 4,
                'vote' => 'Disgree',
                'created_at' => '2022-07-14 23:39:16',
                'updated_at' => '2022-07-14 23:39:16',
            ),
            2 => 
            array (
                'id' => 3,
                'post_id' => 5,
                'vote' => 'vote 1',
                'created_at' => '2022-07-14 23:42:12',
                'updated_at' => '2022-07-14 23:42:12',
            ),
            3 => 
            array (
                'id' => 4,
                'post_id' => 5,
                'vote' => 'vote 2',
                'created_at' => '2022-07-14 23:42:12',
                'updated_at' => '2022-07-14 23:42:12',
            ),
            4 => 
            array (
                'id' => 5,
                'post_id' => 5,
                'vote' => 'vote 3',
                'created_at' => '2022-07-14 23:42:12',
                'updated_at' => '2022-07-14 23:42:12',
            ),
        ));
        
        
    }
}