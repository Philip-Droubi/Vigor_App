<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PostsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('posts')->delete();
        
        \DB::table('posts')->insert(array (
            0 => 
            array (
                'id' => 1,
                'user_id' => 1,
                'text' => 'post type 1 text 👀',
                'is_accepted' => 1,
                'type' => 1,
                'created_at' => '2022-07-14 23:36:01',
                'updated_at' => '2022-07-14 23:36:01',
            ),
            1 => 
            array (
                'id' => 2,
                'user_id' => 1,
                'text' => 'post type 1 text 👀 2',
                'is_accepted' => 1,
                'type' => 1,
                'created_at' => '2022-07-14 23:36:56',
                'updated_at' => '2022-07-14 23:36:56',
            ),
            2 => 
            array (
                'id' => 3,
                'user_id' => 1,
                'text' => 'post type 1 text 👀 2',
                'is_accepted' => 1,
                'type' => 1,
                'created_at' => '2022-07-14 23:37:45',
                'updated_at' => '2022-07-14 23:37:45',
            ),
            3 => 
            array (
                'id' => 4,
                'user_id' => 1,
                'text' => 'post type 2 📊',
                'is_accepted' => 1,
                'type' => 2,
                'created_at' => '2022-07-14 23:39:16',
                'updated_at' => '2022-07-14 23:39:16',
            ),
            4 => 
            array (
                'id' => 5,
                'user_id' => 1,
                'text' => 'post type 3',
                'is_accepted' => 1,
                'type' => 3,
                'created_at' => '2022-07-14 23:42:12',
                'updated_at' => '2022-07-14 23:42:12',
            ),
        ));
        
        
    }
}