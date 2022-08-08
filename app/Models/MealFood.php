<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MealFood extends Model
{
    use HasFactory;
    public $table = 'meal_food';
    public $primarykey = 'id';
    public $fillable = [
        'food_id',
        'meal_id',
        'user_id'
    ];

    public $timestamps = true;

    public function food()
    {
        return $this->belongsTo(Food::class);
    }

    public function meal()
    {
        return $this->belongsTo(Meal::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
