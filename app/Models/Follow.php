<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    use HasFactory;
    protected $table = "follows";
    protected $primaryKey = "id";
    protected $fillable = ['follower_id', 'following'];
    protected $timestamp = true;

    // public function user()
    // {
    //     return $this->belongsTo(User::class);
    // }
}
