<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoachTrainees extends Model
{
    use HasFactory;
    public $table = 'coach_trainees';
    public $primarykey = 'id';
    public $fillable = [
        'coach_id',
        'trainee_id'
    ];
    public $timestamps = true;

    public function coach()
    {
        return $this->belongsTo(User::class,'coach_id');
    }

    public function trainee()
    {
        return $this->belongsTo(User::class,'trainee_id');
    }
}
