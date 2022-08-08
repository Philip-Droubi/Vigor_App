<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkoutExcersises extends Model
{
    use HasFactory;
    public $table = 'workout_excersises';
    public $primarykey = 'id';
    //count length switch
    public $fillable = [
        'excersise_id',
        'workout_id',
        'count',
        'length',
        'user_id'
    ];
    public $timestamps = true;

    public function workout()
    {
        return $this->belongsTo(Workout::class);
    }

    public function excersise()
    {
        return $this->belongsTo(Excersise::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
