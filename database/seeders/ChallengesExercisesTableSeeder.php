<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ChallengesExercisesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('challenges_exercises')->delete();
        
        \DB::table('challenges_exercises')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'run',
                'desc' => 'running',
                'img_path' => '1/RwZ5AmlIzk7lZ2Cve5j0sO3f2mZ8N1running.gif',
                'ca' => '0.032',
                'created_at' => '2022-07-30 02:00:45',
                'updated_at' => '2022-07-30 02:00:45',
            ),
        ));
        
        
    }
}