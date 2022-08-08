<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OauthClientsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        DB::table('oauth_clients')->delete();

        DB::table('oauth_clients')->insert(array (
            0 =>
            array (
                'id' => 1,
                'user_id' => NULL,
                'name' => 'Vigor Personal Access Client',
                'secret' => 'EhDZA8YsNj3MTneFq2tfDfF584oqtiVWQhFWA4LC',
                'provider' => NULL,
                'redirect' => 'http://localhost',
                'personal_access_client' => 1,
                'password_client' => 0,
                'revoked' => 0,
                'created_at' => '2022-07-02 17:09:29',
                'updated_at' => '2022-07-02 17:09:29',
            ),
            1 =>
            array (
                'id' => 2,
                'user_id' => NULL,
                'name' => 'Vigor Password Grant Client',
                'secret' => '3VB321PMXyFmu8xeR8q79y7IRWRA0lW6yEl3SFYG',
                'provider' => 'users',
                'redirect' => 'http://localhost',
                'personal_access_client' => 0,
                'password_client' => 1,
                'revoked' => 0,
                'created_at' => '2022-07-02 17:09:29',
                'updated_at' => '2022-07-02 17:09:29',
            ),
        ));


    }
}
