<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AdmissionFeeSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'type',
        'data',
        'order_index',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'data' => 'array',
        'is_active' => 'boolean',
    ];

    public const TYPE_ADMISSION_CLASS = 'admission_class';
    public const TYPE_ANNUAL_FEE = 'annual_fee';
    public const TYPE_MONTHLY_FEE_CLASS = 'monthly_fee_class';
    public const TYPE_MONTHLY_FEE_OTHER = 'monthly_fee_other';
    public const TYPE_PROPOSED_MONTHLY = 'proposed_monthly';
    public const TYPE_PROPOSED_ANNUAL = 'proposed_annual';

    public const TYPES = [
        self::TYPE_ADMISSION_CLASS,
        self::TYPE_ANNUAL_FEE,
        self::TYPE_MONTHLY_FEE_CLASS,
        self::TYPE_MONTHLY_FEE_OTHER,
        self::TYPE_PROPOSED_MONTHLY,
        self::TYPE_PROPOSED_ANNUAL,
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

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }
}
