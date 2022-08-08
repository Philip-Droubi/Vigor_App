<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DietSubscribe extends Model
{
    use HasFactory;
    public $table = 'diet_subscribes';
    public $primarykey = 'id';
    public $fillable= [
        'diet_id',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function diet()
    {
        return $this->belongsTo(Diet::class);
    }
}
