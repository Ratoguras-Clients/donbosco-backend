<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginActivity extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'ip_address',
        'user_agent',
        'logged_in_at',
        'logged_out_at',
    ];

    public function getUser()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
