<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $table = "posts";
    protected $primaryKey = "id";
    protected $fillable = ['user_id', 'text', 'is_accepted', 'type', 'is_reviewed'];
    protected $timestamp = true;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function Likes()
    {
        return $this->hasMany(PostLike::class, 'post_id');
    }
    public function comments()
    {
        return $this->hasMany(PostComments::class, 'post_id');
    }
    public function votes()
    {
        return $this->hasMany(PostVote::class, 'post_id');
    }
    public function reports()
    {
        return $this->hasMany(PostReport::class, 'post_id');
    }
    public function media()
    {
        return $this->hasMany(PostMedia::class, 'post_id');
    }
}
