<?php

namespace App\Models;

use App\Models\Website\Media;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class OtherOrganization extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'organization_id',
        'name',
        'short_name',
        'slug',
        'mission',
        'description',
        'logo',
        'url',
        'established_date',
        'status',
        'created_by',
        'updated_by',


    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'established_date' => 'date',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
        });

        static::updating(function ($model) {
            if ($model->isDirty('name') && empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
        });
    }


    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }


    public function imageMedia()
    {
        return $this->belongsTo(Media::class, 'logo');
    }


    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }


    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }


    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }
}