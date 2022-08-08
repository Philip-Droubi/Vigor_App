<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call(RolesTableSeeder::class);
        $this->call(AbilitiesTableSeeder::class);
        $this->call(RolesAbilitiesTableSeeder::class);
        $this->call(OauthClientsTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(DiseasesTableSeeder::class);
        $this->call(UsersDevicesTableSeeder::class);
        $this->call(DailyTipsTableSeeder::class);
        $this->call(PostsTableSeeder::class);
        $this->call(FollowsTableSeeder::class);
        $this->call(PostsMediaTableSeeder::class);
        $this->call(PostsVotesTableSeeder::class);
        $this->call(PostCommentsTableSeeder::class);
        $this->call(PostLikesTableSeeder::class);
        $this->call(UsersVotesTableSeeder::class);
        $this->call(ChallengesExercisesTableSeeder::class);
        $this->call(ChallengesTableSeeder::class);
        $this->call(AppFeatureTableSeeder::class);
    }
}
