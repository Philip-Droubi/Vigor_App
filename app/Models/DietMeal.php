<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DietMeal extends Model
{
    use HasFactory;
    public $table = 'diet_meals';
    public $primarykey = 'id';
    public $fillable = [
        'diet_id',
        'meal_id',
        'day'
    ];
    public $timestamps = true;

    public function meal()
    {
        return $this->belongsTo(Meal::class);
    }

    public function diet()
    {
        return $this->belongsTo(Diet::class);
    }
    
}
