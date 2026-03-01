<?php

namespace App\Models;

use App\Models\Website\Media;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class OrganizationStaff extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'media_id',
        'name',
        'email',
        'phone',
        'bio',
        'order_index',
        'is_active',
        'created_by',
        'updated_by',
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

    public function Organization()
    {
        return $this->belongsTo(User::class, 'organization_id');
    }
    public function childrenOrganizations()
    {
        return $this->hasMany(OrganizationStaff::class);
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

    public function getStaffRole()
    {
        return $this->hasOne(OrganizationStaffRole::class);
    }
    public function organizationStaffRole()
    {
        return $this->hasOne(OrganizationStaffRole::class, 'organization_staff_id');
    }
}
