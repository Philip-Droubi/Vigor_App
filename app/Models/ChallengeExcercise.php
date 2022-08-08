<?php

namespace App\Models;

use Illuminate\Broadcasting\Channel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChallengeExcercise extends Model
{
    use HasFactory;
    protected $table = "challenges_exercises";
    protected $primaryKey = "id";
    protected $fillable = ['name', 'desc', 'img_path', 'ca'];
    protected $timestamp = true;

    public function ch()
    {
        return $this->hasMany(Challenge::class, 'ex_id');
    }
}
