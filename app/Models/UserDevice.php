<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDevice extends Model
{
    use HasFactory;
    public $table = "users_devices";
    protected $primaryKey = "id";
    protected $fillable = [
        'user_id',
        'mobile_token'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
