<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChallengeReview extends Model
{
    use HasFactory;
    protected $table = "challenges_reviews";
    protected $primaryKey = "id";
    protected $fillable = ['user_id', 'ch_id', 'stars'];
    protected $timestamp = true;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function ch()
    {
        return $this->belongsTo(Challenge::class);
    }
}
