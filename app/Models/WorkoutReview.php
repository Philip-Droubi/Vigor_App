<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkoutReview extends Model
{
    use HasFactory;
    public $table = 'workout_reviews';
    public $primarykey = 'id';
    public $fillable = [
        'description',
        'stars',
        'user_id',
        'workout_id'
    ];
    public $timestamps = true;

    public function workout()
    {
        return $this->belongsTo(Workout::class, 'workout_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
