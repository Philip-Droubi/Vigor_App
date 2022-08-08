<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewEmail extends Model
{
    use HasFactory;
    protected $table = "new_emails";
    protected $primaryKey = "id";
    protected $fillable = ['user_id', 'new_email', 'email_token', 'back_token'];
    protected $timestamp = true;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
