<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DiseasesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('diseases')->delete();
        
        \DB::table('diseases')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'AAAAAA',
                'created_at' => '2022-07-07 12:32:51',
                'updated_at' => '2022-07-07 12:32:52',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'OOOOOOO',
                'created_at' => '2022-07-07 12:32:52',
                'updated_at' => '2022-07-07 12:32:52',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'EEEEEEE',
                'created_at' => '2022-07-07 12:33:16',
                'updated_at' => '2022-07-07 12:33:16',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'bbbbbb',
                'created_at' => '2022-07-07 12:33:55',
                'updated_at' => '2022-07-07 12:33:55',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'covidoooo',
                'created_at' => '2022-07-09 01:31:13',
                'updated_at' => '2022-07-09 01:31:13',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'covidoooo2',
                'created_at' => '2022-07-09 01:34:16',
                'updated_at' => '2022-07-09 01:34:16',
            ),
        ));
        
        
    }
}