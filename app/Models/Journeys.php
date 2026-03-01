<?php

namespace App\Models;

use App\Models\Website\Media;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Journeys extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'title',
        'description',
        'start_date',
        'end_date',
        'order_index',
        'is_active',
        'media_id',
        'media_id_2',
        'created_by',
        'updated_by',
    ];
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
    public function getCreatedBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function getupdatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    public function media()
    {
        return $this->belongsTo(Media::class, 'media_id');
    }
    public function media2()
    {
        return $this->belongsTo(Media::class, 'media_id_2');
    }
}
