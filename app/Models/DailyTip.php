<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyTip extends Model
{
    use HasFactory;
    protected $table = "daily_tips";
    protected $primaryKey = "id";
    protected $fillable = ['tip'];
    protected $timestamp = true;
}
