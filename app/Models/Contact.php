<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'name',
        'email',
        'phone',
        'message',
        'is_active',
    ];
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
