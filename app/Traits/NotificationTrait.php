<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Carbon\Carbon;

trait NotificationTrait
{
    protected function sendNotification($tokens = [], $title, $body, $page = 'Home Screen')
    {
        $SERVER_API_KEY = env('Server_Key');
        foreach ($tokens as $token) {
            $data = [

                "registration_ids" => [
                    $token->mobile_token
                ],

                "notification" => [
                    "title" => $title,

                    "body" => $body,

                    "sound" => "default"

                ],

                "data" => [
                    "click_action" => "FLUTTER_NOTIFICATION_CLICK",
                    "page" => $page
                ]

            ];
            $dataString = json_encode($data);

            $headers = [
                'X-CSRF-TOKEN' => csrf_token(),

                'Authorization: key=' . $SERVER_API_KEY,

                'Content-Type: application/json',

            ];

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');

            curl_setopt($ch, CURLOPT_POST, true);

            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

            $response = curl_exec($ch);
        }
        return true;
    }
}
