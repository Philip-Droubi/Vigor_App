<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FavoriteWorkout extends Model
{
    use HasFactory;
    public $table = 'favorite_workouts';
    public $primarykey = 'id';
    public $fillable = [
        'user_id',
        'workout_id'
    ];
    public $timestamps = true;

    public function workout()
    {
        return $this->belongsTo(Workout::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
