<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ChallengesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('challenges')->delete();
        
        \DB::table('challenges')->insert(array (
            0 => 
            array (
                'id' => 1,
                'user_id' => 1,
                'ex_id' => 1,
                'name' => 'ch 32',
                'desc' => 'desc2',
                'img_path' => '1/Challenges/1/lmaBr5cjKazsB8zzMxFvcXxz1AnyFYasdf.PNG',
                'is_time' => '0',
                'total_count' => 2600,
                'end_time' => '2022-07-30',
                'created_at' => '2022-07-30 02:02:45',
                'updated_at' => '2022-07-31 02:15:01',
            ),
            1 => 
            array (
                'id' => 2,
                'user_id' => 2,
                'ex_id' => 1,
                'name' => 'Running',
                'desc' => 'This is running challenge to make you start moving',
                'img_path' => 'Default/35mnhgfrewqw34rfvbhy65r4edfgnhgr4e3sxcwtgr4htyuChallenge.PNG',
                'is_time' => '1',
                'total_count' => 5000,
                'end_time' => '2022-08-08',
                'created_at' => '2022-07-30 02:21:49',
                'updated_at' => '2022-07-30 02:21:49',
            ),
        ));
        
        
    }
}