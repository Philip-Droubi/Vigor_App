<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkoutCategorie extends Model
{
    use HasFactory;
    public $table = 'workout_categories';
    public $primarykey = 'id';
    public $fillable = [
        'name',
        'user_id'
    ];
    public $timestamps = true;

    public function workout()
    {
        return $this->hasMany(Workout::class);
    }

    public function coach()
    {
        return $this->belongsTo(User::class,'user_id');
    }

}
