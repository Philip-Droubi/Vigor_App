<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostCommentReport extends Model
{
    use HasFactory;
    protected $table = "posts_comments_reports";
    protected $primaryKey = "id";
    protected $fillable = ['user_id', 'comment_id'];
    protected $timestamp = true;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function comment()
    {
        return $this->belongsTo(PostComments::class);
    }
}
