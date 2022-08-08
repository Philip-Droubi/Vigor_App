<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    use HasFactory;
    public $table = "users_info";
    protected $primaryKey = "id";
    protected $fillable = [
        'user_id',
        'height',
        'weight',
        'height_unit',
        'weight_unit',
        'changed_at',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
