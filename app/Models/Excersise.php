<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Excersise extends Model
{
    use HasFactory;
    public $table = 'excersises';
    public $primarykey = 'id';
    public $fillable = [
        'name',
        'description',
        'burn_calories',
        'length',
        'user_id'
    ];
    public $timestamps = true;

    public function media()
    {
        return $this->hasMany(ExcersiseMedia::class);
    }

    public function workout()
    {
        return $this->hasMany(WorkoutExcersises::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

}
