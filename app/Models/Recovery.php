<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recovery extends Model
{
    use HasFactory;
    public $table = "recoveries";
    protected $primaryKey = "id";
    protected $fillable = [
        'user_id',
        'code',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
