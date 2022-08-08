<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PostCommentsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('post_comments')->delete();
        
        \DB::table('post_comments')->insert(array (
            0 => 
            array (
                'id' => 1,
                'user_id' => 1,
                'post_id' => 1,
                'text' => 'طيييط',
                'created_at' => '2022-07-14 23:43:36',
                'updated_at' => '2022-07-14 23:43:36',
            ),
            1 => 
            array (
                'id' => 2,
                'user_id' => 1,
                'post_id' => 1,
                'text' => 'طيييط222',
                'created_at' => '2022-07-14 23:43:42',
                'updated_at' => '2022-07-14 23:43:42',
            ),
            2 => 
            array (
                'id' => 3,
                'user_id' => 1,
                'post_id' => 1,
                'text' => 'طيييط222',
                'created_at' => '2022-07-14 23:43:43',
                'updated_at' => '2022-07-14 23:43:43',
            ),
        ));
        
        
    }
}