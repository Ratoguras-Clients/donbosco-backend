<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class OrganizationStaffRole extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_staff_id',
        'staff_role_id',
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

    public function getCreatedBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getUpdatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function staffRole()
    {
        return $this->belongsTo(StaffRole::class, 'staff_role_id');
    }

    public function organizationStaff()
    {
        return $this->belongsTo(OrganizationStaff::class, 'organization_staff_id');
    }
}