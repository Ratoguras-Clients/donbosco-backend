<?php

namespace App\Models;

use App\Models\Website\Media;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Notice extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'title',
        'slug',
        'description',
        'attachment',
        'notice_date',
        'priority',
        'is_published',
        'created_by',
    ];

    protected $casts = [
        'notice_date' => 'datetime',
        'is_published' => 'boolean',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->title);
            }
            if (Auth::check()) {
                $model->created_by = Auth::id();
            }
        });

        static::updating(function ($model) {
            if ($model->isDirty('title') && empty($model->slug)) {
                $model->slug = Str::slug($model->title);
            }
        });
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'attachment');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}