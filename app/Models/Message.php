<?php

namespace App\Models;

use App\Models\Website\Media;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_staff_id',
        'organization_id',
        'title',
        'content',
        'date',
        'tenure',
        'is_active',
        'is_home',
        'created_by',
        'updated_by',
    ];
    protected $casts = [
        'is_active' => 'boolean',
        'is_home' => 'boolean',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = auth()->user()->id;
        });

        static::updating(function ($model) {
            $model->updated_by = auth()->user()->id;
        });
    }

    public function organizationStaff()
    {
        return $this->belongsTo(OrganizationStaff::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
    public function getCreatedBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function getUpdatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function media()
    {
        return $this->belongsTo(Media::class, 'media_id');
    }
}
