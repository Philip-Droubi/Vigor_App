<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Practice extends Model
{
    use HasFactory;
    public $table = 'practices';
    public $primarykey = 'id';
    public $fillable = [
        'user_id',
        'workout_id',
        'summar_calories',
        'excersises_played',
        'summary_time'
    ];
    public $timestamps = true;

    public function trainee()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function workout()
    {
        return $this->belongsTo(Workout::class);
    }
}
