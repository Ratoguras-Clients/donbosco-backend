<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamHero extends Model
{
    use HasFactory;
     protected $fillable = [
        'organization_id',
        'title',
        'content',
        'is_home',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_home' => 'boolean',
    ];

  
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
