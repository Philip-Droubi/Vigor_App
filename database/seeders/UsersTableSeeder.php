<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        DB::table('users')->delete();

        DB::table('users')->insert(array (
            0 =>
            array (
                'id' => 1,
                'f_name' => 'philip',
                'l_name' => 'za',
                'email' => 'philipp565dro@gmail.com',
                'password' => '$2y$10$a2HXUNyk8WxM0ni/zBJ5E.I7oGRVEMSUVcwmLipfF0.3yfGR.ob7.',
                'prof_img_url' => '1/profilePic/HUoZKIs41mDwAl2IFHOw3V4P8jXzkSlap1.jpg',
                'gender' => 'male',
                'birth_date' => '2022-07-07',
                'bio' => 'I\'m Omar and I am a King Kong',
                'country' => 'KingSton',
                'email_verified_at' => '2022-07-07 02:13:02',
                'deleted_at' => NULL,
                'role_id' => 3,
                'remember_token' => NULL,
                'created_at' => '2022-07-07 02:13:02',
                'updated_at' => '2022-07-16 14:23:15',
            ),
            1 =>
            array (
                'id' => 2,
                'f_name' => 'shopy app',
                'l_name' => '',
                'email' => 'shopyapp.sy@gmail.com',
                'password' => '$2y$10$VTD7Jl7CbV1J9LiQskvSaeoaU56cXdXulbOm1NkHg91L4QjKWOoSi',
                'prof_img_url' => 'https://lh3.googleusercontent.com/a-/AFdZucoDAtR6D8tj1Btob9xsmsLDBUQ0PHuPhv95lIMS=s96-c',
                'gender' => 'male',
                'birth_date' => '2022-07-07',
                'bio' => 'dsadsadsad',
                'country' => NULL,
                'email_verified_at' => '2022-07-07 02:13:02',
                'deleted_at' => NULL,
                'role_id' => 1,
                'remember_token' => NULL,
                'created_at' => '2022-07-07 02:13:02',
                'updated_at' => '2022-07-07 02:13:02',
            ),
            2 =>
            array (
                'id' => 5,
                'f_name' => 'Vigor',
                'l_name' => 'App',
                'email' => 'test@g.com222',
                'password' => '$2y$10$GYa4DJFdRVsc1Qsj73yiguJyzqhI6xg/vl19ghT/JxREXZR8pZwNu',
                'prof_img_url' => 'Default/Logo/ku76tfgyuytrewedr432qwsdfgtyhnLOGO.png',
                'gender' => 'male',
                'birth_date' => '2022-07-07',
                'bio' => 'App Owner',
                'country' => 'ZIZI',
                'email_verified_at' => '2022-07-07 02:13:02',
                'deleted_at' => NULL,
                'role_id' => 5,
                'remember_token' => NULL,
                'created_at' => '2022-07-07 02:13:02',
                'updated_at' => '2022-07-16 14:22:53',
            ),
            3 =>
            array (
                'id' => 6,
                'f_name' => 'fadi',
                'l_name' => 'asfor',
                'email' => 'test@g.com1',
                'password' => '$2y$10$Wtb4EcD2MrQWglIf2VPx.ewdqIqKkD7lisrZd4ZAIh1elzaJwd00a',
                'prof_img_url' => 'Default/RrmDmqreoLbR6dhjSVuFenDAii8uBWdqhi2fYSjK9pRISPykLSdefaultprofileimg.jpg',
                'gender' => 'male',
                'birth_date' => '2000-01-02',
                'bio' => 'رو من هون رووو',
                'country' => 'GARAMANA',
                'email_verified_at' => '2022-07-07 02:13:02',
                'deleted_at' => NULL,
                'role_id' => 1,
                'remember_token' => NULL,
                'created_at' => '2022-07-07 02:13:02',
                'updated_at' => '2022-07-07 02:13:02',
            ),
            4 =>
            array (
                'id' => 7,
                'f_name' => 'fadi',
                'l_name' => 'bsbose',
                'email' => 'test@g.com2',
                'password' => '$2y$10$CTI6wL/5v1Ej.UDtqo4yh.toA0jEHDSoEoAW3j6w0mryNqWC31vBG',
                'prof_img_url' => 'Default/RrmDmqreoLbR6dhjSVuFenDAii8uBWdqhi2fYSjK9pRISPykLSdefaultprofileimg.jpg',
                'gender' => 'male',
                'birth_date' => '1990-05-05',
                'bio' => 'GG😎',
                'country' => 'Syria',
                'email_verified_at' => '2022-07-07 02:13:02',
                'deleted_at' => NULL,
                'role_id' => 1,
                'remember_token' => NULL,
                'created_at' => '2022-07-07 02:13:02',
                'updated_at' => '2022-07-07 02:13:02',
            ),
            5 =>
            array (
                'id' => 8,
                'f_name' => 'Ammar',
                'l_name' => 'Hunaidi',
                'email' => 'ammar.hunaidi.01@gmail.com',
                'password' => '$2a$12$.drxlrWVNgmzqnVX.n.Jjec5zceJQJKw3JuPUIybsbknjK2dQXmVO',
                'prof_img_url' => 'Default/RrmDmqreoLbR6dhjSVuFenDAii8uBWdqhi2fYSjK9pRISPykLSdefaultprofileimg.jpg',
                'gender' => 'male',
                'birth_date' => '1990-05-05',
                'bio' => '',
                'country' => 'Syria',
                'email_verified_at' => '2022-07-07 02:13:02',
                'deleted_at' => NULL,
                'role_id' => 3,
                'remember_token' => NULL,
                'created_at' => '2022-07-07 02:13:02',
                'updated_at' => '2022-07-07 02:13:02',
            ),
        ));


    }
}
