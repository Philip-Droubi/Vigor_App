<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Challenge extends Model
{
    use HasFactory;
    protected $table = "challenges";
    protected $primaryKey = "id";
    protected $fillable = ['user_id', 'ex_id', 'name', 'desc', 'img_path', 'is_time', 'total_count', 'end_time'];
    protected $timestamp = true;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function reviews()
    {
        return $this->hasMany(ChallengeReview::class, 'ch_id');
    }
    public function subs()
    {
        return $this->hasMany(ChallengeSub::class, 'ch_id');
    }
    public function reports()
    {
        return $this->hasMany(ChallengeReport::class, 'ch_id');
    }
    public function ex()
    {
        return $this->belongsTo(ChallengeExcercise::class);
    }
}
