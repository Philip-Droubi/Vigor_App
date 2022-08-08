<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\UserInfo;

class UserProfileResource extends JsonResource
{
    public function toArray($user)
    {
        $url = User::find($this->id)->prof_img_url;
        if (!(Str::substr($url, 0, 4) == 'http')) {
            $url = 'storage/images/users/' . $url;
        }
        $birth_date = $this->birth_date;
        if (!$birth_date == null) {
            $birth_date = Carbon::parse($birth_date)->format('Y-m-d');
        } else $birth_date = '';
        $info = UserInfo::where('user_id', $this->id)->first();
        $cv = false;
        if ($this->CV()->first())
            $cv = true;
        return [
            'id' =>  $this->id,
            'fname' => (string) $this->f_name,
            'lname' => (string) $this->l_name,
            'email' => (string) $this->email,
            'role_id' =>  $this->role_id,
            'role_name' => (string) $this->role->name,
            'birth_date' => $birth_date,
            'bio' => (string) $this->bio,
            'gender' => (string) $this->gender,
            'country' => (string) $this->country,
            'created_at' =>  $this->created_at->format("Y-m-d"),
            'profile_img' => (string) $url,
            'height' => (string) $info->height,
            'weight' => (string) $info->weight,
            'height_unit' => (string) $info->height_unit,
            'weight_unit' => (string) $info->weight_unit,
            'cv' => $cv,
        ];
    }
}
