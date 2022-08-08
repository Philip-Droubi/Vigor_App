<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostVote extends Model
{
    use HasFactory;
    protected $table = "posts_votes";
    protected $primaryKey = "id";
    protected $fillable = ['post_id', 'vote'];
    protected $timestamp = true;

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
    public function votes()
    {
        return $this->hasMany(UserVote::class, 'vote_id');
    }
}
