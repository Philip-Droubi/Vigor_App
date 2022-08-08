<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UsersDevicesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('users_devices')->delete();

        \DB::table('users_devices')->insert(array(
            0 =>
            array(
                'id' => 1,
                'mobile_token' => 'cAg1SjFWTSWmCmRowFGjr8:APA91bGdktOcIOP6RKrRzbJiFgZ0f7li8pD3FOd2Ubyzp6QehkshPnGlfizGcE6kj172qcAxZEtBVneOISgVAdTZSJjC70lWxDwNWZkM557HhqQ-tcrP1AVFZ2s9cVGyRuloIWUkg6_a',
                'user_id' => 6,
                'created_at' => '2022-07-13 23:39:43',
                'updated_at' => '2022-07-13 23:39:43',
            ),
        ));
    }
}
