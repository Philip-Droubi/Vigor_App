<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    use HasFactory;
    protected $table = "blocked_users";
    protected $primaryKey = "id";
    protected $fillable = ['user_id', 'blocked'];
    protected $timestamp = true;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
