<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'subject',
        'content',
        'status',
        'user_id',
        'start_date',
        'expiration_date'
    ];
}
