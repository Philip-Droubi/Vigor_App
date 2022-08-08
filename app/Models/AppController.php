<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppController extends Model
{
    use HasFactory;
    protected $table = "app_feature";
    protected $primaryKey = "id";
    protected $fillable = ['name', 'is_active'];
    protected $timestamp = true;
}
