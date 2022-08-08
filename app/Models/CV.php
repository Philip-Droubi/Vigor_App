<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CV extends Model
{
    use HasFactory;
    protected $table = "cvs";
    protected $primaryKey = "id";
    protected $fillable = ['user_id', 'description', 'role_id', 'cv_path', 'acception'];
    protected $timestamp = true;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
