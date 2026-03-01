<?php

namespace App\Models;

use App\Models\Website\Media;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Events extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'title',
        'description',
        'start_date',
        'end_date',
        'location',
        'media_id',
        'is_published',
        'is_home',
        'created_by',
    ];
    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'is_published' => 'boolean',
        'is_home'      => 'boolean',
    ];
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
}
