<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecordDisease extends Model
{
    use HasFactory;
    protected $table = "records_diseases";
    protected $primaryKey = "id";
    protected $fillable = ['record_id', 'disease_id'];
    protected $timestamp = true;

    public function record()
    {
        return $this->belongsTo(HealthRecord::class);
    }
}
