<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'icon',
        'organization_id',
        'is_active',
        'created_by',
        'updated_by',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = auth()->id();
        });

        static::updating(function ($model) {
            $model->updated_by = auth()->id();
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

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }
}
