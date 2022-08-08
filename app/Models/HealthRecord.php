<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthRecord extends Model
{
    use HasFactory;
    protected $table = "health_records";
    protected $primaryKey = "id";
    protected $fillable = ['user_id', 'description'];
    protected $timestamp = true;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function diseases()
    {
        return $this->hasMany(RecordDisease::class, 'record_id');
    }
}
