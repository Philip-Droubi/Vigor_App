<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserVote extends Model
{
    use HasFactory;
    protected $table = "users_votes";
    protected $primaryKey = "id";
    protected $fillable = ['vote_id', 'user_id'];
    protected $timestamp = true;

    public function vote()
    {
        return $this->belongsTo(PostVote::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
