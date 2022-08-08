<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExcersiseMedia extends Model
{
    use HasFactory;
    public $table = 'excersise_media';
    public $primarykey = 'id';
    public $fillable = [
        'excersise_id',
        'excersise_medai_url',
        'user_id'
    ];
    public $timestamps = true;
}
