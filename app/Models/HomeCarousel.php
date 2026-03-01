<?php

namespace App\Models;

use App\Models\Website\Media;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeCarousel extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'title',
        'subtitle',
        'media_id',
        'order_index',
        'is_active',
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

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function media()
    {
        return $this->belongsTo(Media::class);
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
