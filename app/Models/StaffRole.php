<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class StaffRole extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active',
        'is_messageable',
        'created_by',
        'updated_by',
    ];
    protected $casts = [
        'is_active' => 'boolean',
        'is_messageable' => 'boolean',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = Auth::id();
        });

        static::updating(function ($model) {
            $model->updated_by = Auth::id();
        });
    }

    public function getCreatedBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getUpdatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
