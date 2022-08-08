<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkoutCategorieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('workout_categories')->insert([
            'name' => 'Full Body',
            'user_id' => 1
        ],
        [
            'name' => 'Chest',
            'user_id' => 1
        ],
        [
            'name' => 'Stomach',
            'user_id' => 1
        ],
        [
            'name' => 'Legs',
            'user_id' => 1
        ]);
    }
}
