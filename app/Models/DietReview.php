<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DietReview extends Model
{
    use HasFactory;
    public $table = 'diet_reviews';
    public $primarykey = 'id';
    public $fillable = [
        'description',
        'stars',
        'user_id',
        'diet_id'
    ];
    public $timestamps = true;

    public function diet()
    {
        return $this->belongsTo(Diet::class,'diet_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
