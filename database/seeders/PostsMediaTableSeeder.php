<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PostsMediaTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('posts_media')->delete();
        
        \DB::table('posts_media')->insert(array (
            0 => 
            array (
                'id' => 1,
                'post_id' => 2,
            'url' => '1/posts/2/y9RbHJDv8IhIQxkyBN6KyC634AoTY9laravel API complete tutorial - 0019- laravel  api get authenticated user data by the generated token ( 360 X 638 ).mp4',
                'created_at' => '2022-07-14 23:36:56',
                'updated_at' => '2022-07-14 23:36:56',
            ),
            1 => 
            array (
                'id' => 2,
                'post_id' => 2,
            'url' => '1/posts/2/gyzfkf4fakEaPkknf7xelafkynQ3Jcمقدمه توضيح عن هذه القائمه - 001 -  laravel API  complete tutorial ( 340 X 640 ).mp4',
                'created_at' => '2022-07-14 23:36:56',
                'updated_at' => '2022-07-14 23:36:56',
            ),
            2 => 
            array (
                'id' => 3,
                'post_id' => 3,
                'url' => '1/posts/3/PJyXXZaElk3bRnehSDj8QkhFYSEYGQmarsh.jpg',
                'created_at' => '2022-07-14 23:37:45',
                'updated_at' => '2022-07-14 23:37:45',
            ),
            3 => 
            array (
                'id' => 4,
                'post_id' => 3,
                'url' => '1/posts/3/dzzcVS4AuM4Iv4TuSayKnQQY5ozkRnmeat.jpg',
                'created_at' => '2022-07-14 23:37:45',
                'updated_at' => '2022-07-14 23:37:45',
            ),
            4 => 
            array (
                'id' => 5,
                'post_id' => 3,
                'url' => '1/posts/3/Mxch5HQUewSKqrzJ7Usy0UgRrTQ2Timirror.jpg',
                'created_at' => '2022-07-14 23:37:45',
                'updated_at' => '2022-07-14 23:37:45',
            ),
            5 => 
            array (
                'id' => 6,
                'post_id' => 3,
                'url' => '1/posts/3/cby7DPxXEQ2xyMNIpK6SvMZtAMYNEvolive.jpg',
                'created_at' => '2022-07-14 23:37:45',
                'updated_at' => '2022-07-14 23:37:45',
            ),
            6 => 
            array (
                'id' => 7,
                'post_id' => 3,
                'url' => '1/posts/3/aBwG4n22NveqUPSbEgB5vJekWp3SUUone.png',
                'created_at' => '2022-07-14 23:37:45',
                'updated_at' => '2022-07-14 23:37:45',
            ),
            7 => 
            array (
                'id' => 8,
                'post_id' => 3,
                'url' => '1/posts/3/nW4IFEZZbIBGvR1AG8GepkLIZZs2Buthree.jpg',
                'created_at' => '2022-07-14 23:37:45',
                'updated_at' => '2022-07-14 23:37:45',
            ),
            8 => 
            array (
                'id' => 9,
                'post_id' => 3,
                'url' => '1/posts/3/WcQP7q3VebjFsqJ2tnLDHi4INI3L2ytow.jpg',
                'created_at' => '2022-07-14 23:37:45',
                'updated_at' => '2022-07-14 23:37:45',
            ),
            9 => 
            array (
                'id' => 10,
                'post_id' => 3,
                'url' => '1/posts/3/ZWOnCnvY0BMvMtvr3fpnU162CzZcAZwindex.jpeg',
                'created_at' => '2022-07-14 23:37:45',
                'updated_at' => '2022-07-14 23:37:45',
            ),
            10 => 
            array (
                'id' => 11,
                'post_id' => 3,
            'url' => '1/posts/3/gDFCPEDcfo37tjkWbAvM0CC8OJ9j6Olaravel API complete tutorial - 015 - understand more about Laravel JWT token ( 360 X 640 ).mp4',
                'created_at' => '2022-07-14 23:37:45',
                'updated_at' => '2022-07-14 23:37:45',
            ),
        ));
        
        
    }
}