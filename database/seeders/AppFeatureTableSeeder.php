<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AppFeatureTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('app_feature')->delete();
        
        \DB::table('app_feature')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'All features',
                'is_active' => 1,
                'created_at' => '2022-08-02 19:49:00',
                'updated_at' => '2022-08-07 11:40:09',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Posts',
                'is_active' => 1,
                'created_at' => '2022-08-02 19:49:44',
                'updated_at' => '2022-08-07 11:45:34',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Post creation',
                'is_active' => 1,
                'created_at' => '2022-08-02 19:52:00',
                'updated_at' => '2022-08-02 19:52:00',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Regist',
                'is_active' => 1,
                'created_at' => '2022-08-02 22:04:13',
                'updated_at' => '2022-08-02 22:04:13',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Workouts Creation',
                'is_active' => 1,
                'created_at' => '2022-08-08 14:33:56',
                'updated_at' => '2022-08-08 14:33:56',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Diets Creation',
                'is_active' => 1,
                'created_at' => '2022-08-08 14:33:56',
                'updated_at' => '2022-08-08 14:33:56',
            ),
        ));
        
        
    }
}