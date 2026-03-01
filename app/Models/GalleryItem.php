<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Website\Media;

class GalleryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'collection_id',
        'title',
        'media_id',
        'order_index',
        'is_active',
        'is_cover',       
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_cover'  => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function media()
    {
        return $this->belongsTo(Media::class, 'media_id');
    }

    public function collection()
    {
        return $this->belongsTo(Collection::class, 'collection_id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }
}