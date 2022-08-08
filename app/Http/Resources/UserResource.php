<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;

class UserResource extends JsonResource
{

    public function toArray($user)
    {
        $url = User::find($this->id)->prof_img_url;
        if (!(Str::substr($url, 0, 4) == 'http')) {
            $url = 'storage/images/users/' . $url;
        }
        return [
            'id' => $this->id,
            'f_name' => (string) $this->f_name,
            'l_name' => (string) $this->l_name,
            'email' => (string) $this->email,
            'role_id' => $this->role_id,
            'role_name' => (string) $this->role->name,
            'profile_img' => (string) $url,
        ];
    }
}
