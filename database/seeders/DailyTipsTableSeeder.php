<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DailyTipsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('daily_tips')->delete();
        
        \DB::table('daily_tips')->insert(array (
            0 => 
            array (
                'id' => 1,
                'tip' => 'IF IT DOSEN\'T CHALLENGE YOU IT DOSEN\'T CHANGE YOU. ',
                'created_at' => '2022-07-14 16:20:44',
                'updated_at' => '2022-07-14 16:20:44',
            ),
            1 => 
            array (
                'id' => 2,
                'tip' => 'No PAIN no GAIN 💪.',
                'created_at' => '2022-07-14 16:20:44',
                'updated_at' => '2022-07-14 16:20:44',
            ),
            2 => 
            array (
                'id' => 3,
                'tip' => 'Challenge yourself and keep going 💪.',
                'created_at' => '2022-07-14 16:20:44',
                'updated_at' => '2022-07-14 16:20:44',
            ),
            3 => 
            array (
                'id' => 4,
                'tip' => 'You can\'t spell CHAlleNGE without CHANGE .',
                'created_at' => '2022-07-14 16:20:44',
                'updated_at' => '2022-07-14 16:20:44',
            ),
        ));
        
        
    }
}