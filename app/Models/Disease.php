<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disease extends Model
{
    use HasFactory;
    protected $table = "diseases";
    protected $primaryKey = "id";
    protected $fillable = ['name'];
    protected $timestamp = true;

    public function records()
    {
        return $this->hasMany(RecordDisease::class);
    }
}
